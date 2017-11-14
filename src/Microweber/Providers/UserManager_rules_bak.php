<?php

/*
 * This file is part of Microweber
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Laravel\Socialite\SocialiteManager;
use Illuminate\Support\Facades\Session;
use Auth;
use QueryPath\Exception;
use User;
use Admin;
use UsersExtend;
use SiteInfo;
use RedisServer;
use Backup;
use Microweber\Install;
use File;
use Carbon;
use Cache;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Role;
use App\Permission;
use OauthSession;
use Template as Templates;
use Statistics;

if (!defined('MW_USER_IP')) {
    if (isset($_SERVER['REMOTE_ADDR'])) {
        define('MW_USER_IP', $_SERVER['REMOTE_ADDR']);
    } else {
        define('MW_USER_IP', '127.0.0.1');
    }
}

//
class UserManager
{
    public $tables = array();

    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        $this->set_table_names();

        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
        $this->socialite = new SocialiteManager($this->app);
    }

    public function set_table_names($tables = false)
    {
        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['users'])) {
            $tables['users'] = 'users';
        }
        if (!isset($tables['users_extends'])) {
        	$tables['users_extends'] = 'users_extends';
        }
        if (!isset($tables['log'])) {
            $tables['log'] = 'log';
        }
        $this->tables['users'] = $tables['users'];
        $this->tables['users_extends'] = $tables['users_extends'];
        $this->tables['log'] = $tables['log'];
    }

    public function is_admin_user()
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->id;
        } else {
            return false;
        }
    }

    public function is_admin()
    {
        if (!mw_is_installed()) {
            return false;
        }
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->id;
        }
        if ($this->is_edit()) {
            return 'editor_id:' . $this->is_edit();
        }
        return false;
    }

    public function admin_user_id()
    {
        if (!mw_is_installed()) {
            return false;
        }
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->id;
        }
    }

    public function admin_id()
    {
        if (is_admin()) {
            return Auth::guard('admin')->check();
        } else {
            return false;
        }
    }

    public function is_edit()
    {
        if (!empty($this->session_get('editor_id'))) {
            return $this->session_get('editor_id');
        }

        if (!empty($this->session_get('admin_editor_id'))) {
            return $this->session_get('admin_editor_id');
        }
        return false;
    }

    public function is_view()
    {
        if (!empty($this->session_get('visitor_id'))) {
            return $this->session_get('visitor_id');
        }
        return false;
    }


    public function id()
    {

        if (Auth::check()) {
            return Auth::user()->id;
        }
        return false;
    }

    /**
     * Allows you to login a user into the system.
     *
     * It also sets user session when the user is logged. <br />
     * On 5 unsuccessful logins, blocks the ip for few minutes <br />
     *
     *
     * @param array|string $params You can pass parameter as string or as array.
     * @param mixed|string $params ['email'] optional If you set  it will use this email for login
     * @param mixed|string $params ['password'] optional Use password for login, it gets trough $this->hash_pass() function
     *
     * @example
     * <code>
     * //login with username
     * $this->login('username=test&password=pass')
     * </code>
     * @example
     * <code>
     * //login with email
     * $this->login('email=my@email.com&password=pass')
     * </code>
     * @example
     * <code>
     * //login hashed password
     * $this->login('email=my@email.com&password_hashed=c4ca4238a0b923820dcc509a6f75849b')
     * </code>
     *
     * @return array|bool
     *
     * @category Users
     *
     * @uses     $this->hash_pass()
     * @uses     parse_str()
     * @uses     $this->get_all()
     * @uses     $this->session_set()
     * @uses     $this->app->log_manager->get()
     * @uses     $this->app->log_manager->save()
     * @uses     $this->login_set_failed_attempt()
     * @uses     $this->update_last_login_time()
     * @uses     $this->app->event_manager->trigger()
     * @function $this->login()
     *
     * @see      _table() For the database table fields
     */
    public function login($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $check = $this->app->log_manager->get('no_cache=1&count=1&updated_at=[mt]1 min ago&is_system=y&rel_type=login_failed&user_ip=' . MW_USER_IP);
        $url = $this->app->url->current(1);
        if ($check == 5) {
            $url_href = "<a href='$url' target='_blank'>$url</a>";
            $this->app->log_manager->save('title=User IP ' . MW_USER_IP . ' is blocked for 1 minute for 5 failed logins.&content=Last login url was ' . $url_href . '&is_system=n&rel_type=login_failed&user_ip=' . MW_USER_IP);
        }
        if ($check > 5) {
            $check = $check - 1;

            return array('error' => _e('fail_pelase_login_later_in_10mintus', true));
        }
        $check2 = $this->app->log_manager->get('no_cache=1&is_system=y&count=1&created_at=[mt]10 min ago&updated_at=[lt]10 min&rel_type=login_failed&user_ip=' . MW_USER_IP);
        if ($check2 > 25) {
            return array('error' => _e('fail_pelase_login_later_25mintus', true));
        }

        $login_captcha_enabled = get_option('user_login_captcha_enabled', 'users') == 'y';
        if ($login_captcha_enabled) {
            if (!isset($params['captcha'])) {
                return array('error' => _e('captcha_not_match', true));
            }
            $validate_captcha = $this->app->captcha->validate($params['captcha'], null, false);
            if (!$validate_captcha) {
                return array('error' => _e('captcha_not_match', true), 'captcha_error' => true);
            }
        }

        $override = $this->app->event_manager->trigger('mw.user.before_login', $params);

        $redirect_after = isset($params['redirect']) ? $params['redirect'] : false;
        $overiden = false;
        $return_resp = false;
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    $return_resp = $resp;
                    $overiden = true;
                }
            }
        }
        if ($overiden == true and $redirect_after != false) {
            return $this->app->url_manager->redirect($redirect_after);
        } elseif ($overiden == true) {
            return $return_resp;
        }
        $old_sid = Session::getId();

        if (isset($params['username'])) {
            $ok = Auth::attempt([
                'username' => $params['username'],
                'password' => $params['password'],
            ]);

            if (!$ok) {
                if ($params['username'] != false and filter_var($params['username'], FILTER_VALIDATE_EMAIL)) {
                    $ok = Auth::attempt([
                        'email' => $params['username'],
                        'password' => $params['password'],
                    ]);
                }
            }
        } elseif (isset($params['email'])) {
            $ok = Auth::attempt([
                'email' => $params['email'],
                'password' => $params['password'],
            ]);
        }

        if (!isset($ok)) {
            return;
        }
        if ($ok) {

            $user = Auth::login(Auth::user());
            $user_data = $this->get_by_id(Auth::user()->id);
            if ($user_data['is_active'] != 1) {
                $this->logout();
                return array('error' => _e("user is banned to login in", true));
            }
            $user_data['old_sid'] = $old_sid;
            $this->get_ssesion($user_data);
            $this->app->event_manager->trigger('mw.user.login', $user_data);
            if ($ok && $redirect_after) {
                return $this->app->url_manager->redirect($redirect_after);
            } elseif ($ok) {
                return ['success' => _e("you-are-logged-in", true)];
            }
        } else {
            $this->login_set_failed_attempt();
        }
        return array('error' => _e("please_set_username", true));
    }

    public function user_log()
    {
        $ssid = Session::getId();
        $user_id = user_id();

        if (!empty(admin_user_id())) {
            return array('status' => 'online');
        }

        if (empty($user_id)) {
            return array('status' => 'offline');
        } else {
            $oauth_sessions = DB::table('oauth_sessions')->where('owner_id', $user_id)->first();
            if (empty($oauth_sessions)) {
                return array('status' => 'online');
            }
            if ($ssid !== $oauth_sessions->client_redirect_uri) {
                $this->logout();
                return array('status' => 'offline');
            } else {
                return array('status' => 'online');
            }
        }
    }

    public function get_ssesion($params)
    {
        $oauth_sessions = DB::table('oauth_sessions')->where('owner_id', $params['id'])->first();
        if (empty($oauth_sessions)) {
            $sessions = new OauthSession();
            $sessions->owner_id = $params['id'];
            $sessions->client_redirect_uri = Session::getId();;
            $sessions->client_id = 'pc';
            $sessions->save();
        } else {
            $sessions = OauthSession::find($oauth_sessions->id);
            $sessions->client_redirect_uri = Session::getId();;
            $sessions->save();
        }
    }

    public function logout($params = false)
    {
        //   Session::flush();
        $aj = $this->app->url_manager->is_ajax();
        $redirect_after = isset($_GET['redirect']) ? $_GET['redirect'] : false;
        if ($redirect_after == false) {
            $redirect_after = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : false;
        }
        if (isset($_COOKIE['editmode'])) {
            setcookie('editmode');
        }
        $this->app->user_manager->session_set('editmode', false);
        $this->app->user_manager->session_set('editor_id', false);
        $this->app->user_manager->session_set('visitor_id', false);

        Auth::guard()->logout();

        $this->app->event_manager->trigger('mw.user.logout', $params);

        if ($redirect_after == false and $aj == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                return $this->app->url_manager->redirect($_SERVER['HTTP_REFERER']);
            }
        }

        if ($redirect_after == true) {
            $redir = $redirect_after;
            // $redir = site_url($redirect_after);
            return $this->app->url_manager->redirect($redir);
        }
        return true;
    }

    public function admin_logout_edit($params = false)
    {
        $this->app->user_manager->session_set('editmode', false);
        $this->app->user_manager->session_set('editor_id', false);
        $this->app->user_manager->session_set('visitor_id', false);
        return $this->app->url_manager->redirect(site_url() . 'admin');
    }

    public function admin_logout($params = false)
    {

        //echo "<pre>";print_r(Session::all());exit;
        $url = $this->app->user_manager->session_get('main_domain');
        $url = site_url() . '/admin';
        // Session::flush();
        $aj = $this->app->url_manager->is_ajax();
        $redirect_after = isset($_GET['redirect']) ? $_GET['redirect'] : false;
        if ($redirect_after == false) {
            $redirect_after = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : false;
        }
        if (isset($_COOKIE['editmode'])) {
            setcookie('editmode');
        }
        $this->app->user_manager->session_set('editmode', false);
        $this->app->user_manager->session_set('editor_id', false);
        $this->app->user_manager->session_set('visitor_id', false);
        Auth::guard('admin')->logout();

        $this->app->event_manager->trigger('mw.admin.logout', $params);

        if (!empty($url)) {
            return $this->app->url_manager->redirect($this->format_url($url));
        }

        if ($redirect_after == false and $aj == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                return $this->app->url_manager->redirect($_SERVER['HTTP_REFERER']);
            }
        }

        if ($redirect_after == true) {
            $redir = $redirect_after;

            // $redir = site_url($redirect_after);
            return $this->app->url_manager->redirect($redir);
        }

        return true;
    }

    public function is_logged()
    {
        if (Auth::check()) {
            return true;
        } else {
            return false;
        }
    }

    public function login_as($params)
    {
        $is_a = $this->is_admin();
        if ($is_a == true) {
            return true;
        }
    }

    public function has_access($function_name)
    {
        // will be updated with roles and perms
        $is_a = $this->is_admin();
        if ($is_a == true) {
            return true;
        } else {
            return false;
        }
    }

    public function admin_login($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $check = $this->app->log_manager->get('no_cache=1&count=1&updated_at=[mt]1 min ago&is_system=y&rel_type=login_failed&user_ip=' . MW_USER_IP);
        $url = $this->app->url->current(1);
        if ($check == 5) {
            $url_href = "<a href='$url' target='_blank'>$url</a>";
            $this->app->log_manager->save('title=User IP ' . MW_USER_IP . ' is blocked for 1 minute for 5 failed logins.&content=Last login url was ' . $url_href . '&is_system=n&rel_type=login_failed&user_ip=' . MW_USER_IP);
        }
        if ($check > 5) {
            $check = $check - 1;

            return array('error' => _e('fail_pelase_login_later_in_10mintus', true));
        }
        $check2 = $this->app->log_manager->get('no_cache=1&is_system=y&count=1&created_at=[mt]10 min ago&updated_at=[lt]10 min&rel_type=login_failed&user_ip=' . MW_USER_IP);
        if ($check2 > 25) {
            return array('error' => _e('fail_pelase_login_later_in_10mintus', true));
        }

        $login_captcha_enabled = get_option('login_captcha_enabled', 'users') == 'y';
        if ($login_captcha_enabled) {
            if (!isset($params['captcha'])) {
                return array('error' => _e('Please enter the captcha answer!', true));
            }
            $validate_captcha = $this->app->captcha->validate($params['captcha'], null, false);
            if (!$validate_captcha) {
                return array('error' => _e('Invalid captcha answer!', true), 'captcha_error' => true);
            }
        }

        $override = $this->app->event_manager->trigger('mw.admin.before_login', $params);

        $redirect_after = isset($params['redirect']) ? $params['redirect'] : false;
        $overiden = false;
        $return_resp = false;
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    $return_resp = $resp;
                    $overiden = true;
                }
            }
        }

        if ($overiden == true and $redirect_after != false) {
            return $this->app->url_manager->redirect($redirect_after);
        } elseif ($overiden == true) {
            return $return_resp;
        }
        $old_sid = Session::getId();
        if (isset($params['username'])) {
            $user_info = DB::table('admins')->where('username', $params['username'])->first();
            if (!empty($user_info)) {
                if ($user_info->is_active != 1) {
                    return array('error' => _e("user is banned to login in", true));
                }
            }
            $ok = Auth::guard('admin')->attempt([
                'username' => $params['username'],
                'password' => $params['password'],
            ]);

            if (!$ok) {
                $user_info = DB::table('admins')->where('email', $params['username'])->first();
                if (!empty($user_info)) {
                    if ($user_info->is_active != 1) {
                        return array('error' => _e("user is banned to login in", true));
                    }
                }
                if ($params['username'] != false and filter_var($params['username'], FILTER_VALIDATE_EMAIL)) {
                    $ok = Auth::guard('admin')->attempt([
                        'email' => $params['username'],
                        'password' => $params['password'],
                    ]);

                }
            }
        } elseif (isset($params['email'])) {
            $user_info = DB::table('admins')->where('email', $params['email'])->first();
            if (!empty($user_info)) {
                if ($user_info->is_active != 1) {
                    return array('error' => _e("user is banned to login in", true));
                }
            }
            $ok = Auth::guard('admin')->attempt([
                'email' => $params['email'],
                'password' => $params['password'],
            ]);

        }

        if (!isset($ok)) {
            return;
        }
        if ($ok) {

            $user = Auth::guard('admin')->login(Auth::guard('admin')->user());
            $this->set_table_names(array('users' => 'admins'));
            $user_data = $this->get_by_id(Auth::guard('admin')->user()->id);
            $user_data['old_sid'] = $old_sid;
            $this->app->event_manager->trigger('mw.admin.login', $user_data);


            /*$where=array();
            $where['owner_id']=Auth::guard('admin')->user()->id;
            $res = DB::table('oauth_sessions')->delete();	*/

            if ($ok && $redirect_after) {
                return $this->app->url_manager->redirect($redirect_after);
            } elseif ($ok) {
                return ['success' => 'You are logged in!'];
            }
        } else {
            $this->login_set_failed_attempt();
        }

        return array('error' => _e('please enter password', true));
    }

    public function admin_access()
    {
        if ($this->is_admin() == false && $this->is_edit() == false) {
            exit('You must be logged as admin');
        }
    }

    public function attributes($user_id = false)
    {
        if (!$user_id) {
            $user_id = $this->id();
        }
        if (!$user_id) {
            return;
        }

        $data = array();
        $data['rel_type'] = 'users';
        $data['rel_id'] = intval($user_id);
        $res = array();
        $get = $this->app->attributes_manager->get($data);
        if (!empty($get)) {
            foreach ($get as $item) {
                if (isset($item['attribute_name']) and isset($item['attribute_value'])) {
                    $res[$item['attribute_name']] = $item['attribute_value'];
                }
            }
        }
        if (!empty($res)) {
            return $res;
        }

        return $get;
    }

    public function data_fields($user_id = false)
    {
        if (!$user_id) {
            $user_id = $this->id();
        }
        if (!$user_id) {
            return;
        }

        $data = array();
        $data['rel_type'] = 'users';
        $data['rel_id'] = intval($user_id);
        $res = array();
        $get = $this->app->content_manager->get_data($data);
        if (!empty($get)) {
            foreach ($get as $item) {
                if (isset($item['field_name']) and isset($item['field_value'])) {
                    $res[$item['field_name']] = $item['field_value'];
                }
            }
        }
        if (!empty($res)) {
            return $res;
        }

        return $get;
    }

    public function picture($user_id = false)
    {

        if (!$user_id) {
            $user_id = $this->id();
        }

        $name = $this->get_by_id($user_id);
        if (isset($name['thumbnail']) and $name['thumbnail'] != '') {
            return $name['thumbnail'];
        } else {
            //添加如果没有用户的默认图片
            // return site_url()."/userfiles/modules/microweber/images/face.png";
        }
    }

    /**
     * @function user_name
     * gets the user's FULL name
     *
     * @param        $user_id the id of the user. If false it will use the curent user (you)
     * @param string $mode full|first|last|username
     *                        'full' //prints full name (first +last)
     *                        'first' //prints first name
     *                        'last' //prints last name
     *                        'username' //prints username
     *
     * @return string
     */
    public function name($user_id = false, $mode = 'full')
    {
        if ($mode != 'username') {
            if ($user_id == user_id()) {
                return 'You';
            }
        }
        if ($user_id == false) {
            $user_id = user_id();
        }

        $name = $this->nice_name($user_id, $mode);

        return $name;
    }

    /**
     * Function to get user printable name by given ID.
     *
     * @param        $id
     * @param string $mode
     *
     * @return string
     *
     * @example
     * <code>
     * //get user name for user with id 10
     * $this->nice_name(10, 'full');
     * </code>
     *
     * @uses $this->get_by_id()
     */
    public function nice_name($id = false, $mode = 'full')
    {

        if (!$id) {
            $id = $this->id();
        }

        $user = $this->get_by_id($id);


        $user_data = $user;
        if (empty($user)) {
            return false;
        }

        switch ($mode) {
            case 'first' :
            case 'fist' :
                // because of a common typo :)
                $user_data['first_name'] ? $name = $user_data['first_name'] : $name = $user_data['username'];
                $name = ucwords($name);

                if (trim($name) == '' and $user_data['email'] != '') {
                    $n = explode('@', $user_data['email']);
                    $name = $n[0];
                }
                // return $name;
                break;

            case 'last' :
                $user_data['last_name'] ? $name = $user_data['last_name'] : $name = $user_data['last_name'];
                $name = ucwords($name);
                break;

            case 'username' :
                $name = $user_data['username'];
                break;


            case 'email' :
                $name = $user_data['email'];
                break;

            case 'full' :
            default :

                $name = '';
                if (isset($user_data['first_name'])) {
                    if ($user_data['first_name']) {
                        $name = $user_data['first_name'];
                    }
                }

                if (isset($user_data['last_name'])) {
                    if ($user_data['last_name']) {
                        $name .= ' ' . $user_data['last_name'];
                    }
                }
                $name = ucwords($name);

                if (trim($name) == '' and $user_data['email'] != '') {
                    $name = $user_data['email'];
                    $name_from_email = explode('@', $user_data['email']);
                    $name = $name_from_email[0];
                }

                if (trim($name) == '' and $user_data['username'] != '') {
                    $name = $user_data['username'];
                    $name = ucwords($name);
                }

                break;
        }

        if (!isset($name) or $name == false or $name == null or trim($name) == '') {
            if (isset($user_data['username']) and $user_data['username'] != false and trim($user_data['username']) != '') {
                $name = $user_data['username'];
            } elseif (isset($user_data['email']) and $user_data['email'] != false and trim($user_data['email']) != '') {
                $name_from_email = explode('@', $user_data['email']);
                $name = $name_from_email[0];
            }
        }

        return $name;
    }

    public function api_login($api_key = false)
    {
        if ($api_key == false and isset($_REQUEST['api_key']) and user_id() == 0) {
            $api_key = $_REQUEST['api_key'];
        }

        if ($api_key == false) {
            return false;
        } else {
            if (trim($api_key) == '') {
                return false;
            } else {
                if (user_id() > 0) {
                    return true;
                } else {
                    $data = array();
                    $data['api_key'] = $api_key;
                    $data['is_active'] = 1;
                    $data['limit'] = 1;

                    $data = $this->get_all($data);

                    if ($data != false) {
                        if (isset($data[0])) {
                            $data = $data[0];

                            if (isset($data['api_key']) and $data['api_key'] == $api_key) {
                                return $this->make_logged($data['id']);
                            }
                        }
                    }
                }
            }
        }
    }

    public function register($params)
    {

        if (defined('MW_API_CALL')) {
            //	if (isset($params['token'])){
            if ($this->is_admin() == false) {
                $validate_token = $this->csrf_validate($params);
                if ($validate_token == false) {
                    return array('error' => _e('Invalid token!', true));
                }
            }
            //}
        }
        $user = isset($params['username']) ? $params['username'] : false;
        $pass = isset($params['password']) ? $params['password'] : false;
        $email = isset($params['email']) ? $params['email'] : false;
        $first_name = isset($params['first_name']) ? $params['first_name'] : false;
        $last_name = isset($params['last_name']) ? $params['last_name'] : false;
        $middle_name = isset($params['middle_name']) ? $params['middle_name'] : false;
        $confirm_password = isset($params['confirm_password']) ? $params['confirm_password'] : false;
        $pass2 = $pass;

        $no_captcha = get_option('captcha_disabled', 'users') == 'y';
        $disable_registration_with_temporary_email = get_option('disable_registration_with_temporary_email', 'users') == 'y';
        if ($email != false and $disable_registration_with_temporary_email) {
            $checker = new \Microweber\Utils\lib\DisposableEmailChecker();
            $is_temp_email = $checker->check($email);
            if ($is_temp_email) {
                $domain = substr(strrchr($email, "@"), 1);
                return array('error' => _e('cannot_register_from') . $domain . ' domain');
            }
        }
        $override = $this->app->event_manager->trigger('before_user_register', $params);
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    return $resp;
                }
            }
        }

        if (defined('MW_API_CALL')) {
            if (isset($params['is_admin']) and $this->is_admin() == false) {
                unset($params['is_admin']);
            }
            if (isset($params['is_verified']) and $this->is_admin() == false) {
                unset($params['is_verified']);
            }
        }

        if (!isset($params['username']) or (empty($params['username']))) {
            return array('error' => _e("please_set_username", true));
        }

        if (!isset($params['password']) or ($params['password']) == '') {
            return array('error' => _e('palese_set_password', true));
        }

        if (!isset($params['email']) or $params['email'] == '') {
            return array('error' => _e('Please enter email!', true));
            //$params['username']=$params['email'];
        }

        if (!$no_captcha) {
            if (!isset($params['captcha'])) {
                return array('error' => _e('captcha_not_match', true));
            } else {
                $validate_captcha = $this->app->captcha->validate($params['captcha'], null, false);
                if (!$validate_captcha) {
                    return array('error' => _e('captcha_not_match', true), 'captcha_error' => true);
                }
            }

        }

        if (isset($params['password']) and ($params['password']) != '') {
            if (($confirm_password != false) or ($confirm_password == '')) {
                if ($params['password'] != $confirm_password) {
                    return array('error' => _e("password_not_mactth", true));
                }
            }

            if ($email != false or $user != false) {


                $data = array();
                $data['email'] = $email;
                $data['one'] = true;
                $data['no_cache'] = true;
                $user_email_data = $this->get_all($data);

                if (empty($user_email_data)) {
                    $data = array();
                    $data['username'] = $user;
                    $data['one'] = true;
                    $data['no_cache'] = true;
                    $user_username_data = $this->get_all($data);

                }

                if (empty($user_email_data) and empty($user_username_data)) {

                    $data = array();
                    $data['username'] = $user;
                    $data['email'] = $email;
                    $data['password'] = $pass;
                    $data['is_active'] = 1;
                    $table = $this->tables['users'];

                    $reg = array();
                    $reg['username'] = $user;
                    $reg['email'] = $email;
                    $reg['password'] = $pass2;
                    $reg['is_active'] = 1;
                    if ($first_name != false) {
                        $reg['first_name'] = $first_name;
                    }
                    if ($first_name != false) {
                        $reg['first_name'] = $first_name;
                    }
                    if ($last_name != false) {
                        $reg['last_name'] = $last_name;
                    }
                    if ($middle_name != false) {
                        $reg['middle_name'] = $middle_name;
                    }

                    $this->force_save = true;
                    if (isset($params['attributes'])) {
                        $reg['attributes'] = $params['attributes'];
                    }
                    $ip = $this->get_use_ip();
                    $userIp = User::where(['user_ip' => $ip])->count();
                    if($userIp && !is_admin()) {
                        return array('error' => _e("已达到注册上线", true));
                    } else {
                        $reg['user_ip'] = $ip;
                    }
                    $next = $this->save($reg);

                    $this->force_save = false;
                    $this->app->cache_manager->delete('users/global');
                    $this->session_del('captcha');

                    $this->after_register($next);

                    $params = $data;
                    $params['id'] = $next;
                    if (isset($pass2)) {
                        $params['password2'] = $pass2;
                    }
                    $this->make_logged($params['id']);

                    return array('success' => _e("register_success", true));

                } else {
                    // 验证码
                    /* $try_login = $this->login($params);

                     if (isset($try_login['success'])) {
                         return $try_login;
                     }*/
                    return array('error' => _e("user_already_exsit", true));
                }
            }
        }
    }
    
    // 官网注册页面
    public function register_page($params) 
    {	
    	if (defined('MW_API_CALL')) {
    		if ($this->is_admin() == false) {
    			$validate_token = $this->csrf_validate($params);
    			if ($validate_token == false) {
    				return array('error' => _e('Invalid token!', true));
    			}
    		}
    	}

    	$user = isset($params['username']) ? $params['username'] : false;
    	$pass = isset($params['password']) ? $params['password'] : false;
    	$email = isset($params['email']) ? $params['email'] : false;
    	$first_name = isset($params['first_name']) ? $params['first_name'] : false;
    	$last_name = isset($params['last_name']) ? $params['last_name'] : false;
    	$middle_name = isset($params['middle_name']) ? $params['middle_name'] : false;
    	$confirm_password = isset($params['confirm_password']) ? $params['confirm_password'] : false;
    	$pass2 = $pass;
    	
    	$company_name = isset($params['cname']) ? $params['cname'] : false;
    	$countrycode = isset($params['areacode']) ? $params['areacode'] : false;
    	$countrycode = str_replace("+","00",trim($countrycode));
    	$phone = isset($params['phone']) ? $params['phone'] : false;

    	$no_captcha = get_option('captcha_disabled', 'users') == 'y';

    	$override = $this->app->event_manager->trigger('before_user_register', $params);

    	if (is_array($override)) {
    		foreach ($override as $resp) {
    			if (isset($resp['error']) or isset($resp['success'])) {
    				return $resp;
    			}
    		}
    	}
    	
    	if (defined('MW_API_CALL')) {
    		if (isset($params['is_admin']) and $this->is_admin() == false) {
    			unset($params['is_admin']);
    		}
    		if (isset($params['is_verified']) and $this->is_admin() == false) {
    			unset($params['is_verified']);
    		}
    	}

    	if (!isset($params['username']) or (empty($params['username']))) {
    		return array('error' => _e("please_set_username", true));
    	}
    	
    	if (!isset($params['password']) or ($params['password']) == '') {
    		return array('error' => _e('palese_set_password', true));
    	}
    	
    	if (!isset($params['email']) or $params['email'] == '') {
    		return array('error' => _e('Please enter email!', true));
    	}

    	if (isset($params['password']) and ($params['password']) != '') {
    		if (($confirm_password != false) or ($confirm_password == '')) {
    			if ($params['password'] != $confirm_password) {
    				return array('error' => _e("password_not_mactth", true));
    			}
    		}
    	
    		if ($email != false or $user != false) {
    			$data = array();
    			$data['email'] = $email;
    			$data['one'] = true;
    			$data['no_cache'] = true;
    			$user_email_data = $this->get_all($data);

    			if (empty($user_email_data)) {
    				$data = array();
    				$data['username'] = $user;
    				$data['one'] = true;
    				$data['no_cache'] = true;
    				$user_username_data = $this->get_all($data);
    			}

    			if (empty($user_email_data) and empty($user_username_data)) {
    	
    				$data = array();
    				$data['username'] = $user;
    				$data['email'] = $email;
    				$data['password'] = $pass;
    				$data['is_active'] = 1;
    				$table = $this->tables['users'];
    				$table_extends = $this->tables['users_extends'];
    				
    				$reg = array();
    				$reg['username'] = $user;
    				$reg['email'] = $email;
    				$reg['password'] = $pass2;
    				$reg['is_active'] = 1;
    				if ($first_name != false) {
    					$reg['first_name'] = $first_name;
    				}
    				if ($first_name != false) {
    					$reg['first_name'] = $first_name;
    				}
    				if ($last_name != false) {
    					$reg['last_name'] = $last_name;
    				}
    				if ($middle_name != false) {
    					$reg['middle_name'] = $middle_name;
    				}
    	
    				$this->force_save = true;
    				if (isset($params['attributes'])) {
    					$reg['attributes'] = $params['attributes'];
    				}
    				$ip = $this->get_use_ip();
    				$userIp = User::where(['user_ip' => $ip])->count();
    				if($userIp && !is_admin()) {
    					return array('error' => _e("the_IP_has_been_used", true));
    				} else {
    					$reg['user_ip'] = $ip;
    				}

    				$next = $this->save($reg);
    				if(isset($next) and $next !== false) {		

    					$users_extends = array();
    					$users_extends['user_id'] = intval($next);
    					$users_extends['username'] = $reg['username'];
    					$users_extends['email'] = $reg['email'];
    					$users_extends['countrycode'] = $countrycode;
    					$users_extends['mobile'] = $phone;
    					$users_extends['company_name'] = $company_name;

    					/*$rules = array(
    						'username' => 'between:4,20',
    						'email' => 'required|email|unique:users,email',
    						'countrycode' => 'required|numeric',
    						'mobile' => 'required|numeric|min:5|max:11',
    						'company_name' => 'min:3|max:60',
    					);
    					$messages = [
    						'username.between' => 'The :attribute must be between :min - :max.',
    						'email.required' => 'We need to know your e-mail address!',
    						'countrycode.required' => 'The area verification code must exist',
    						'mobile.required' => 'The phone number must exist',
    						'company_name.max' => 'Company_name no more than sixty characters',
    					];
    					$validator = Validator::make($users_extends, $rules);*/
    					
    					$rules = [
    					'email' => 'required|email|unique:users,email',
    					];
    					
    					$input = Input::only(
    							'email'
    					);
    					$messages = array( 'unique' => 'This email is already subscribed!' );
    					$validator = Validator::make($input, $rules,$messages);
    					
    					if ($validator->fails()) {
    						return array('error' => $validator->messages());
    					}
    					echo '222';
    				die;	
    					
    					$users_extends_id = $this->app->database_manager->save($table_extends, $users_extends);
    					if(isset($users_extends_id) && empty($users_extends_id)) {
    						return array('error' => _e("user_extends_error", true));
    					}
    				}
    				$this->force_save = false;
    				$this->app->cache_manager->delete('users/global');
    				$this->session_del('captcha');
    	
    				$this->after_register($next);
    	
    				$params = $data;
    				$params['id'] = $next;
    				if (isset($pass2)) {
    					$params['password2'] = $pass2;
    				}
    				$this->make_logged($params['id']);
    	
    				return array('success' => _e("register_success", true));
    	
    			} else {
    				return array('error' => _e("user_already_exsit", true));
    			}
    		}
    	}
    }

    public function after_register($user_id, $suppress_output = true)
    {
        if ($suppress_output == true) {
            ob_start();
        }
        $data = $this->get_by_id($user_id);
        if (!$data) {
            return;
        }
        $notif = array();
        $notif['module'] = 'users';
        $notif['rel_type'] = 'users';
        $notif['rel_id'] = $user_id;
        $notif['title'] = 'New user registration';
        $notif['description'] = 'You have new user registration';
        $notif['content'] = 'You have new user registered with the username [' . $data['username'] . '] and id [' . $user_id . ']';

        $this->app->notifications_manager->save($notif);

        $this->app->log_manager->save($notif);
        $this->register_email_send($user_id);

        $this->app->event_manager->trigger('mw.user.after_register', $data);
        if ($suppress_output == true) {
            ob_end_clean();
        }
    }

    public function register_email_send($user_id = false)
    {
        if ($user_id == false) {
            $user_id = $this->id();
        }
        if ($user_id == false) {
            return;
        }
        $data = $this->get_by_id($user_id);
        if (!$data) {
            return;
        }
        if (is_array($data)) {
            $register_email_enabled = $this->app->option_manager->get('register_email_enabled', 'users');
            if ($register_email_enabled == true) {
                $register_email_subject = $this->app->option_manager->get('register_email_subject', 'users');
                $register_email_content = $this->app->option_manager->get('register_email_content', 'users');
                if ($register_email_subject == false or trim($register_email_subject) == '') {
                    $register_email_subject = 'Thank you for your registration!';
                }
                $to = $data['email'];
                if ($register_email_content != false and trim($register_email_subject) != '') {
                    if (!empty($data)) {
                        foreach ($data as $key => $value) {
                            if (!is_array($value) and is_string($key)) {
                                $register_email_content = str_ireplace('{' . $key . '}', $value, $register_email_content);
                            }
                        }
                    }
                    $verify_email_link = $this->app->format->encrypt($data['id']);
                    $verify_email_link = api_url('users/verify_email_link') . '?key=' . $verify_email_link;
                    $register_email_content = str_ireplace('{verify_email_link}', $verify_email_link, $register_email_content);


                    if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                        $sender = new \Microweber\Utils\MailSender();
                        return $sender->send($to, $register_email_subject, $register_email_content);

                    }
                }
            }
        }
    }

    public function csrf_validate(&$data)
    {
        $session_token = Session::token();
        if (is_array($data) and $this->session_id()) {
            foreach ($data as $k => $v) {
                if ($k == 'token' or $k == '_token') {
                    if ($session_token === $v) {
                        unset($data[$k]);

                        return true;
                    }
                }
            }
        }
    }

    public function hash_pass($pass)
    {
        $hash = \Hash::make($pass);

        return $hash;
    }

    /**
     * Allows you to save users in the database.
     *
     * By default it have security rules.
     *
     * If you are admin you can save any user in the system;
     *
     * However if you are regular user you must post param id with the current user id;
     *
     * @param  $params
     * @param  $params ['id'] = $user_id; // REQUIRED , you must set the user id.
     *                 For security reasons, to make new user please use user_register() function that requires captcha
     *                 or write your own save_user wrapper function that sets  mw_var('force_save_user',true);
     *                 and pass its params to save_user();
     * @param  $params ['is_active'] = 1; //default is 'n'
     *
     * @usage
     *
     * $upd = array();
     * $upd['id'] = 1;
     * $upd['email'] = $params['new_email'];
     * $upd['password'] = $params['passwordhash'];
     * mw_var('force_save_user', false|true); // if true you want to make new user or foce save ... skips id check and is admin check
     * mw_var('save_user_no_pass_hash', false|true); //if true skips pass hash function and saves password it as is in the request, please hash the password before that or ensure its hashed
     * $s = save_user($upd);
     *
     * @return bool|int
     */
    public $force_save = false;

    function getDomains($params = false)
    {
        //判断KEY是否合法
        $token = isset($params['token']) ? $params['token'] : '';
        if (!isset($token) || $token == '') {
            _e('token is empty');
            return;
        }
        $app_key = Config::get('app.key');
        $o_token = sha1('api' . md5($app_key));
        if ($o_token != $token) {
            _e('token is invalid');
            return;
        }
        $info = SiteInfo::get();
        $site_info = array();
        foreach ($info as $key => $val) {
            $site_info[$val->site_url] = $val->conf_dir;
        }
        echo json_encode($site_info);
    }

    function getStaticsData($params)
    {

        $users_last5 = get_visits('last5');
        $users_online = get_visits('users_online');
        $staticsdata['onlineusers'] = intval($users_online);
        foreach ($users_last5 as $key => $item) {
            $users_last5[$key]['last_page'] = str_replace('{SITE_URL}', site_url(), $users_last5[$key]['last_page']);
            $users_last5[$key]['last_page'] = str_replace('{SITE_URL}', site_url(), $users_last5[$key]['last_page']);
            $users_last5[$key]['visit_date'] = date("d M, Y", strtotime($users_last5[$key]['visit_date'])) . ' - ' . date('H:i', strtotime($users_last5[$key]['visit_time']));
        }
        $staticsdata['users_last5'] = $users_last5;
        $data = json_encode($staticsdata);
        return !empty($data) ? $data : array();
    }

    public function check_name_or_email($params)
    {
        if (!isset($params['username'])) {
            $arr['error'] = _e('User name must be 6 to 20 bit', true);
            return $arr;
        }
        $user = DB::table('users')->where('username', $params['username'])->first();
        $email = DB::table('users')->where('email', $params['username'])->first();
        if (empty($email) && empty($user)) {
            $arr['error'] = _e('username or email not exsist'
                , true);
            return $arr;

        } else {
            $arr['success'] = _e('ok', true);
            return $arr;
        }
    }

    public function get_captcha($params)
    {
        if (!isset($params['captcha'])) {
            return array('error' => _e('captcha not alow be null', true));
        } else {
            $validate_captcha = $this->app->captcha->validate($params['captcha'], null, false);
            if (!$validate_captcha) {
                return array('error' => _e('captcha not match', true), 'captcha_error' => true);
            } else {
                $arr['success'] = _e('ok', true);
                return $arr;
            }
        }
    }

    public function check_name($params)
    {
        if (!isset($params['username'])) {
            $arr['error'] = _e('User name must be 6 to 20 bit', true);
            return $arr;
        }
        $rules = array(
            'username' => 'between:4,20',
        );
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            $arr['error'] = _e('User name must be 4 to 20 bit', true);
            return $arr;
        }
        $admin = DB::table('users')->where('username', $params['username'])->first();

        if (empty($admin)) {
            $arr['success'] = _e('ok', true);

            return $arr;
        } else {

            if (!isset($params['id']) || $params['id'] != $admin->id) {
                $arr['error'] = _e('User name exsist', true);
                return $arr;
            } else {
                $arr['success'] = _e('ok', true);
                return $arr;
            }
        }
    }

    /*colby 新功能 后台用户
     * */
    public function change_active($params)
    {

        $user = User::find($params['id']);
        $user->is_active = $params['is_active'];

        if ($user->save()) {
            $arr['success'] = _e('ok', true);

            return $arr;
        } else {
            $arr['error'] = _e('User name exsist', true);

            return $arr;
        }
    }

    /*colby 新功能 后台用户
     * */
    public function change_siteinfo($params)
    {

        $siteInfo = SiteInfo::find($params['id']);
        $siteInfo->is_delete = 1;

        if ($siteInfo->save()) {
            $arr['success'] = _e('ok', true);

            return $arr;
        } else {
            $arr['error'] = _e('User name exsist', true);

            return $arr;
        }
    }

    /*colby 新功能 后台用户
     * */
    public function change_freeze($params)
    {

        $siteInfo = SiteInfo::find($params['id']);
        $siteInfo->is_freeze = $params['is_freeze'];
        if ($siteInfo->save()) {
            $arr['success'] = _e('ok', true);

            return $arr;
        } else {
            $arr['error'] = _e('User name exsist', true);

            return $arr;
        }
    }

    /*colby 新功能 后台用户
     * */
    public function change_sitenumber($params)
    {

        $user = User::find($params['id']);
        $user->site_number = $params['site_number'];

        if ($user->save()) {
            $arr['success'] = _e('ok', true);

            return $arr;
        } else {
            $arr['error'] = _e('User name exsist', true);

            return $arr;
        }
    }

    /*colby 新功能 上传模板
     * */
    public function template_upload($name, $tname)
    {
        $dir = getcwd() . '/userfiles/modules/';
        move_uploaded_file($tname, $dir . $name);
        $src_file = $dir . $name;
        $dest_dir = false;
        $create_zip_name_dir = false;
        $overwrite = true;
        if ($zip = zip_open($src_file)) {
            if ($zip) {
                $splitter = ($create_zip_name_dir === true) ? "." : "/";
                if ($dest_dir === false) {
                    $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter)) . "/";
                }

                // 如果不存在 创建目标解压目录
                $this->create_dirs($dest_dir);

                // 对每个文件进行解压
                while ($zip_entry = zip_read($zip)) {
                    // 文件不在根目录
                    $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
                    if ($pos_last_slash !== false) {
                        // 创建目录 在末尾带 /
                        $this->create_dirs($dest_dir . substr(zip_entry_name($zip_entry), 0, $pos_last_slash + 1));
                    }
                    // 打开包
                    if (zip_entry_open($zip, $zip_entry, "r")) {

                        // 文件名保存在磁盘上
                        $file_name = $dest_dir . zip_entry_name($zip_entry);
                        $filename = basename($file_name);
                        if (strpos($filename, '.')) {
                            $suffix = strtolower(substr(strrchr($filename, '.'), 1));
                            if ($suffix == 'php' || $suffix == 'js' || $suffix == 'css' || $suffix == 'jpg' || $suffix == 'png') {
                                // 检查文件是否需要重写
                                if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
                                    $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                                    @file_put_contents($file_name, $fstream);
                                    chmod($file_name, 0777);
                                }
                            } else {
                                continue;
                            }
                        } else {
                            // 检查文件是否需要重写
                            if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
                                $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                                @file_put_contents($file_name, $fstream);
                                @chmod($file_name, 0777);

                            }

                        }
                        zip_entry_close($zip_entry);
                    }
                }
                zip_close($zip);
                unlink($src_file);
            }
        } else {
            return false;
        }
        $version = Redis::get('module_version');
        if ($version) {
            Redis::set('module_version', $version + 1);
        } else {
            Redis::set('module_version', 1);
        }
        return true;
    }

    public function get_moudle_version()
    {
        //Redis::set('module_version', 1);
        $version = Redis::get('module_version');
        return $version;
    }

    /**
     * @return \Microweber\Application
     */
    public function create_dirs($path)
    {
        if (!is_dir($path)) {
            $directory_path = "";
            $directories = explode("/", $path);
            array_pop($directories);

            foreach ($directories as $directory) {
                $directory_path .= $directory . "/";
                if (!is_dir($directory_path)) {
                    mkdir($directory_path);
                    chmod($directory_path, 0777);
                }
            }
        }
    }

    public function check_email($params)
    {
        if (!isset($params['email'])) {
            $arr['error'] = _e('email error', true);
            return $arr;
        }
        $rules = array(
            'email' => 'email',
        );
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            $arr['error'] = _e('email format woring', true);
            return $arr;
        }
        $admin = DB::table('users')->where('email', $params['email'])->first();
        if (empty($admin)) {
            $arr['success'] = _e('ok', true);
            return $arr;
        } else {
            if (!isset($params['id']) || $params['id'] != $admin->id) {
                $arr['error'] = _e('email exsist', true);
                return $arr;
            } else {
                $arr['success'] = _e('ok', true);
                return $arr;
            }
        }
    }

    public function check_admin_name($params)
    {
        if (!isset($params['username'])) {
            $arr['error'] = _e('User name must be 6 to 20 bit', true);
            return $arr;
        }
        $rules = array(
            'username' => 'between:4,20',
        );
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            $arr['error'] = _e('User name must be 4 to 20 bit', true);
            return $arr;
        }
        $admin = DB::table('admins')->where('username', $params['username'])->first();
        if (empty($admin)) {
            $arr['success'] = _e('ok', true);
            return $arr;
        } else {
            if (!isset($params['id']) || $params['id'] != $admin->id) {
                $arr['error'] = _e('User name exsist', true);
                return $arr;
            } else {
                $arr['success'] = _e('ok', true);
                return $arr;
            }
        }
    }

    public function check_admin_email($params)
    {
        if (!isset($params['email'])) {
            $arr['error'] = _e('email error', true);
            return $arr;
        }
        $rules = array(
            'email' => 'email',
        );
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            $arr['error'] = _e('email format woring', true);
            return $arr;
        }
        $admin = DB::table('admins')->where('email', $params['email'])->first();
        if (empty($admin)) {
            $arr['success'] = _e('ok', true);
            return $arr;
        } else {
            if (!isset($params['id']) || $params['id'] != $admin->id) {
                $arr['error'] = _e('email exsist', true);
                return $arr;
            } else {
                $arr['success'] = _e('ok', true);
                return $arr;
            }
        }
    }

    public function get_roles_by_uid($id)
    {
        $admin = DB::table('role_user')->where('user_id', $id)->first();
        return $admin;
    }

    public function save_admin($params)
    {

        $force = false;
        if (defined('MW_FORCE_USER_SAVE')) {
            $force = MW_FORCE_USER_SAVE;
        } elseif ($this->force_save) {
            $force = $this->force_save;
        } elseif (mw_var('force_save_user')) {
            $force = mw_var('force_save_user');
        }
        if (!$force) {
            if (defined('MW_API_CALL') and mw_is_installed() == true) {
                if (isset($params['is_admin']) and $this->is_admin() == false and !is_null(User::first())) {
                    unset($params['is_admin']);
                }
            }
        }
        if ($force == false) {
            if (isset($params['id']) and $params['id'] != 0) {
                $adm = $this->is_admin();
                if ($adm == false) {
                    $is_logged = user_id();
                    if ($is_logged == false or $is_logged == 0) {
                        return array('error' => 'You must be logged to save user');
                    } elseif (intval($is_logged) == intval($params['id']) and intval($params['id']) != 0) {
                        // the user is editing their own profile
                    } else {
                        return array('error' => 'You must be logged to as admin save this user');
                    }
                }
            } else {
                if (defined('MW_API_CALL') and mw_is_installed() == true) {
                    $adm = $this->is_admin();
                    if ($adm == false) {
                        $params['id'] = $this->id();
                        $is_logged = user_id();
                        if (intval($params['id']) != 0 and $is_logged != $params['id']) {
                            return array('error' => 'You must be logged save your settings');
                        }
                    } else {
                        if (!isset($params['id'])) {
                            $params['id'] = $this->id();
                        }
                    }
                }
            }
        }
        $data_to_save = $params;

        if (isset($data_to_save['id']) and $data_to_save['id'] != 0 and isset($data_to_save['email']) and $data_to_save['email'] != false) {
            $old_user_data = $this->getadmin_by_id($data_to_save['id']);
            if (isset($old_user_data['email']) and $old_user_data['email'] != false) {
                if ($data_to_save['email'] != $old_user_data['email']) {
                    if (isset($old_user_data['password_reset_hash']) and $old_user_data['password_reset_hash'] != false) {
                        $hash_cache_id = md5(serialize($old_user_data)) . uniqid() . rand();
                        $data_to_save['password_reset_hash'] = $hash_cache_id;
                    }
                }
            }
        }
        if (isset($data_to_save['email']) and isset($data_to_save['id'])) {
            $email = trim($data_to_save['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_existing = array();
                $check_existing['email'] = $email;
                $check_existing['single'] = 1;
                $check_existing['is_admin'] = 1;
                $check_existing = $this->get_all($check_existing);

                if (isset($check_existing['id']) and $check_existing['id'] != $data_to_save['id']) {
                    return array('error' => 'User with this email already exists! Try different email address!');
                }
            }
        }


        if (isset($params['id']) and intval($params['id']) != 0) {

            $user = Admin::find($params['id']);
        } else {
            $user = new Admin();
        }
        $id_to_return = false;

        $data_to_save = $this->app->format->clean_xss($data_to_save);
        if ($user->validateAndFill($data_to_save)) {
            $save = $user->save();
            if (isset($user->id)) {
                $data_to_save['id'] = $params['id'] = $user->id;
            }


            if (isset($data_to_save['username']) and $data_to_save['username'] != false and isset($data_to_save['id']) and $data_to_save['id'] != false) {
                $check_existing = array();
                $check_existing['username'] = $data_to_save['username'];
                $check_existing['single'] = 1;
                $check_existing['is_admin'] = 1;
                $check_existing = $this->get_all($check_existing);
                if (isset($check_existing['id']) and $check_existing['id'] != $data_to_save['id']) {
                    return array('error' => 'User with this username already exists! Try different username!');
                }
            }


            if (isset($params['attributes']) or isset($params['data_fields'])) {
                $params['extended_save'] = true;
            }

            if (isset($params['extended_save'])) {
                if (isset($data_to_save['password'])) {
                    unset($data_to_save['password']);
                }

                if (isset($data_to_save['id'])) {
                    $data_to_save['table'] = 'admins';
                    $this->app->database_manager->extended_save($data_to_save);
                }
            }
            if (isset($params['id']) and intval($params['id']) != 0) {
                $id_to_return = intval($params['id']);
            } else {
                $id_to_return = DB::getPdo()->lastInsertId();
            }
            $params['id'] = $id_to_return;

            if (isset($params['admin_group_id'])) {
                $admin = Role::find($params['admin_group_id']);
                $user->detachRoles();
                $user->roles()->attach($admin->id);
            }
            $this->app->event_manager->trigger('mw.user.save', $params);
        } else {
            return array('error' => 'Error saving the user!');
        }
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . $id_to_return);
        return $id_to_return;
    }

    protected function copy_database($origin_database, $destion_database)
    {
        //创建数据库

        $new_databases_name = $destion_database['database'];
        $new_databases_name = str_ireplace('www.', '', $new_databases_name);
        $new_databases_name = strtolower($new_databases_name);
        $new_databases_name = str_replace('-', '_', $new_databases_name);
        $new_databases_name = str_replace('.', '_', $new_databases_name);

        $installer = new Install\DbCreateer();
        $installer->run($new_databases_name);
        $databases_name = $origin_database['database'];

        $databases_name = str_ireplace('www.', '', $databases_name);
        $databases_name = strtolower($databases_name);
        $databases_name = str_replace('-', '_', $databases_name);
        $databases_name = str_replace('.', '_', $databases_name);


        $filename = "backup-" . Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '-' . $databases_name . ".sql";
        $command = env('MYSQLDUMP_PATH') . " --user=" . env('SERVER_USERNAME') . " --password=" . env('SERVER_PASSWORD') . " --host=" . env('SERVER_HOST') . " " . $databases_name . "  > " . storage_path() . "/" . $filename;

        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);
        if (!$returnVar) {
            //导入文件并删除文件
            $command = env('MYSQL_PATH') . " --user=" . env('SERVER_USERNAME') . " --password=" . env('SERVER_PASSWORD') . " --host=" . env('SERVER_HOST') . " " . $new_databases_name . "  < " . storage_path() . "/" . $filename;
            $returnVar = NULL;
            $output = NULL;
            exec($command, $output, $returnVar);
            File::delete(storage_path() . "/" . $filename);
            return !(bool)$returnVar;

        } else {
            return false;
        }
    }

    public function remove_config($destion_domain)
    {
        $destion_domain = str_ireplace('http://', '', $destion_domain);
        $destion_domain = str_ireplace('https://', '', $destion_domain);
        $destion_domain = str_ireplace('www.', '', $destion_domain);
        $destion_domain = strtolower($destion_domain);

        $path = $this->app->configPath() . '/' . $destion_domain;
        if (File::exists($path)) {
            return File::deleteDirectory($path);
        }
    }

    protected function copy_config($origin_doamin, $destion_domain, $use_other_db = null)
    {
        $destion_domain = str_ireplace('http://', '', $destion_domain);
        $destion_domain = str_ireplace('https://', '', $destion_domain);
        $www_domain = $destion_domain;
        $destion_domain = str_ireplace('www.', '', $destion_domain);
        $destion_domain = strtolower($destion_domain);
        $path = $this->app->configPath() . '/' . $destion_domain;
        $curenvironment = $this->app->environment();
        $domain = $destion_domain;
        $this->app->detectEnvironment(function () use ($domain) {
            $domain = str_ireplace('www.', '', $domain);
            $domain = strtolower($domain);
            return $domain;
        });
        $directory = \Config::get('cache.stores.file.path') . '/' . $this->app->environment();
        Config::set('webconfig.website.website_title', 'name');
        Config::set('webconfig.website.is_publish', 0);
        Config::save('webconfig');
        $this->app->detectEnvironment(function () use ($curenvironment) {
            return $curenvironment;
        });
        if (!@file_exists($path)) {
            File::makeDirectory($path, 0775, true);
        } else {
            File::deleteDirectory($path);
            File::makeDirectory($path, 0775, true);
        }
        $origin_doamin = str_ireplace('http://', '', $origin_doamin);
        $origin_doamin = str_ireplace('https://', '', $origin_doamin);
        $www_domain = $origin_doamin;
        $origin_doamin = str_ireplace('www.', '', $origin_doamin);
        $origin_doamin = strtolower($origin_doamin);
        $origin_path = $this->app->configPath() . '/' . $origin_doamin;

        File::copy("$origin_path/database.php", "$path/database.php");
        File::chmod("$path/database.php", 0755);
        File::copy("$origin_path/microweber.php", "$path/microweber.php");
        File::chmod("$path/microweber.php", 0755);
        File::copy("$origin_path/webconfig.php", "$path/webconfig.php");
        File::chmod("$path/webconfig.php", 0755);
        $curenvironment = $this->app->environment();
        $domain = $destion_domain;
        $this->app->detectEnvironment(function () use ($domain) {
            $domain = str_ireplace('www.', '', $domain);
            $domain = strtolower($domain);
            return $domain;
        });
        //Config::set('webconfig',$webconfig);
        //更改新站点的数据库名称为
        if (!empty($use_other_db)) {
            $destion_domain = $use_other_db;
        }
        $destion_domain = str_ireplace('www.', '', $destion_domain);
        $destion_domain = str_ireplace('www.', '', $destion_domain);
        $environment = str_replace('-', '_', $destion_domain);
        $environment = str_replace('.', '_', $environment);

        $dbname = $environment;
        $dbDriver = 'mysql';
        Config::set("database.connections.$dbDriver.database", $dbname);
        Config::save(array('database', 'microweber', 'webconfig'));
        //切换回主站环境
        $this->app->detectEnvironment(function () use ($curenvironment) {
            return $curenvironment;
        });
        RedisServer::hmset('domainsmap', array($www_domain => $www_domain));
        return true;
    }

    //根据模板站点的ID复制
    function copy_site($params)
    {
        if (!isset($params['id']) || intval($params['id']) == 0) {
            return array('error' => _e('Site Info Id Needed', true));
        } else {
            $siteinfo = SiteInfo::find($params['id']);
        }
        if (!isset($params['new_site_url']) || empty($params['new_site_url'])) {
            return array('error' => _e('Site new url needed', true));
        }
        if (empty($siteinfo)) {
            return array('error' => _e('Site data empty', true));
        }
        //判断模板的网站是否存在，存在则返回错误
        $info = SiteInfo::where('site_url', $params['new_site_url'])->where('is_delete', 0)->first();
        if (sizeof($info) != 0) {
            return array('error' => _e('SiteInfoExisted', true));
        }
        $origin_database['database'] = $siteinfo['site_url'];
        $destion_database['database'] = $params['new_site_url'];
        ini_set('memory_limit', '512M');
        set_time_limit(0);


        //判断改数据库是否被占用
        $new_databases_name = $params['new_site_url'];
        $new_databases_name = str_ireplace('www.', '', $new_databases_name);
        $new_databases_name = strtolower($new_databases_name);
        $new_databases_name = str_replace('-', '_', $new_databases_name);
        $new_databases_name = str_replace('.', '_', $new_databases_name);
        $info = SiteInfo::where('db_name', $new_databases_name)->where('is_delete', 0)->first();
        //被占用,使用新的数据库
        if (!empty($info)) {
            $new_db_name = $new_databases_name . time();
            $data_to_save['db_name'] = $new_db_name;
            $destion_database['database'] = $new_db_name;
        } else {
            $new_db_name = null;
        }


        //删除之前storge的文件
        $ori_domain = str_ireplace('http://', '', $params['new_site_url']);
        $ori_domain = str_ireplace('https://', '', $ori_domain);
        $ori_domain = str_ireplace('www.', '', $ori_domain);
        $ori_domain = strtolower($ori_domain);
        $path = storage_path() . '/framework/cache/' . $ori_domain;
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }


        $res = $this->copy_database($origin_database, $destion_database);

        if (!$res) {
            return _e('database_error');
        }

        $res = $this->copy_config($siteinfo['site_url'], $params['new_site_url'], $new_db_name);
        if (!$res) {
            return _e('config_error');
        }
        if (!isset($params['user_id'])) {
            $data_to_save['user_id'] = $siteinfo->user_id;
        } else {
            $data_to_save['user_id'] = intval($params['user_id']);
        }
        $templates = new Templates();

        $data = $templates->where('is_delete', 0)->where('diable_show', 1)->where('demo_url', $siteinfo['site_url'])->first();
        if ($data) {
            $master_id = $data['id'];
        } else {
            $master_id = null;
        }


        $data_to_save['site_url'] = $params['new_site_url'];
        $data_to_save['site_name'] = $siteinfo['site_name'] . '_' . _e('copy', true);
        $data_to_save['conf_dir'] = $params['new_site_url'];
        $data_to_save['master_id'] = $master_id;
        $data_to_save['is_master'] = 1;
        $data_to_save['is_delete'] = 0;
        $data_to_save['is_publish'] = 0;
        $data_to_save['public_time']=date('Y-m-d h:i:s', time());
        if (empty($siteinfo->site_thumb_url)) {
            $siteinfo->site_thumb_url = '{SITE_URL}userfiles/templates/templates1/screenshot.png';
        }
        $data_to_save['site_thumb_url'] = $siteinfo->site_thumb_url;
        $data_to_save['cloud_id'] = 0;
        $data_to_save['defualt_lang'] = $siteinfo['defualt_lang'];
        $newsiteinfo = new SiteInfo();
        $data_to_save = $this->app->format->clean_xss($data_to_save);
        if ($newsiteinfo->validateAndFill($data_to_save)) {
            $save = $newsiteinfo->save();
            $val = $data_to_save['conf_dir'];
            RedisServer::hmset('domainsmap', array($params['new_site_url'] => $val));
            $id_to_return = DB::getPdo()->lastInsertId();
        } else {
            return array('error' => _e('SiteInfoIdNeeded', true));
        }
        return array('success' => $data_to_save);
    }

    function copy_template($params)
    {
        if (!isset($params['id']) || intval($params['id']) == 0) {
            return array('error' => _e('Site Info Id Needed', true));
        } else {
            $template = Templates::find($params['id']);
        }

        if (!isset($params['new_site_url']) || empty($params['new_site_url'])) {
            return array('error' => _e('Site new url needed', true));
        }
        if (empty($template)) {
            return array('error' => _e('template data empty', true));
        }
        //判断模板的网站是否存在，存在则返回错误
        $info = SiteInfo::where('site_url', $params['new_site_url'])->where('is_delete', 0)->first();
        if (sizeof($info) != 0) {
            return array('error' => _e('SiteInfoExisted', true));
        }

        $origin_database['database'] = $template['demo_url'];
        $destion_database['database'] = $params['new_site_url'];
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $res = $this->copy_database($origin_database, $destion_database);
        if (!$res) {
            return _e('database_error');
        }
        $res = $this->copy_config($template['demo_url'], $params['new_site_url']);
        if (!$res) {
            return _e('config_error');
        }

        $data_to_save['user_id'] = intval($params['user_id']);
        $data_to_save['site_url'] = $params['new_site_url'];
        $data_to_save['site_name'] = $template['demo_url'] . '_' . _e('copy', true);
        $data_to_save['conf_dir'] = $params['new_site_url'];
        $data_to_save['master_id'] = 0;
        $data_to_save['is_master'] = 1;
        $data_to_save['is_delete'] = 0;
        $data_to_save['is_publish'] = 0;
        if (empty($template['thumbnails'])) {
            $template['thumbnails'] = '{SITE_URL}userfiles/templates/templates1/screenshot.png';
        }
        $data_to_save['site_thumb_url'] = $template['thumbnails'];
        $data_to_save['cloud_id'] = 0;
        $data_to_save['defualt_lang'] = 'en';
        $newsiteinfo = new SiteInfo();
        $data_to_save = $this->app->format->clean_xss($data_to_save);
        if ($newsiteinfo->validateAndFill($data_to_save)) {
            $save = $newsiteinfo->save();
            $val = $data_to_save['conf_dir'];
            RedisServer::hmset('domainsmap', array($params['new_site_url'] => $val));
            $id_to_return = DB::getPdo()->lastInsertId();
        } else {
            return array('error' => _e('SiteInfoIdNeeded', true));
        }
        return array('success' => $data_to_save);
    }

    function save_user_site_info($params)
    {

        if (!isset($params['id']) || intval($params['id']) == 0) {
            return _e('SiteInfoIdNeeded', true);
        }


        $siteinfo = SiteInfo::find($params['id']);

        // 当前创建网站用户ID  非本人创建不允许删除   ten
        if ($siteinfo->user_id !== strval(user_id())) {
            return _e('Non user created, not allowed to delete', true);
        }


        $oldsiteinfo = $siteinfo;
        $old_site_url = $oldsiteinfo['site_url'];
        $old_db_name = $oldsiteinfo['db_name'];
        $old_is_publish = $oldsiteinfo['is_publish'];
        $old_site_name = $oldsiteinfo['site_name'];
        if (empty($siteinfo)) {
            return _e('SiteInfoIdNeeded', true);
        }

        //查询数据库里面改域名是否被绑定了
        if (empty($params['site_url'])) {
            return _e('SiteUrlEmpty', true);
        }

        $params['site_url'] = str_ireplace('http://', '', $params['site_url']);
        $params['site_url'] = str_ireplace('https://', '', $params['site_url']);
        $params['site_url'] = strtolower($params['site_url']);
        $destion_domain = $params['site_url'];
        $www_destion_domain = $destion_domain;
        $www_destion_domain = strtolower($www_destion_domain);
        $destion_domain = str_ireplace('www.', '', $destion_domain);

        $info = SiteInfo::where('site_url', $www_destion_domain)->where('is_delete', 0)->first();
        if (!empty($info)) {
            if ($info->id != $params['id']) {
                $arr['error'] = _e('domain already exsit', true);
                return $arr;
            }
        }


        if (!isset($params['is_delete']) || $params['is_delete'] != 1) {
            if (!isset($params['is_publish'])) {
                if (!mw()->template_manager->checkTarget($www_destion_domain, Config::get('domains.dns'))) {
                    return array('error' => _e('domain_dns_errors', true));
                }
            }

        }

        $destion_path = $this->app->configPath() . '/' . $destion_domain;
        $params['conf_dir'] = $destion_domain;

        //更改域名的时候删除缓存文件
        if (isset($params['site_url']) && $params['site_url'] != $old_site_url) {
            $ori_domain = str_ireplace('http://', '', $params['site_url']);
            $ori_domain = str_ireplace('https://', '', $ori_domain);
            $ori_domain = str_ireplace('www.', '', $ori_domain);
            $ori_domain = strtolower($ori_domain);
            $path = storage_path() . '/framework/cache/' . $ori_domain;
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }

            $ori_domain = str_ireplace('http://', '', $old_site_url);
            $ori_domain = str_ireplace('https://', '', $ori_domain);
            $ori_domain = str_ireplace('www.', '', $ori_domain);
            $ori_domain = strtolower($ori_domain);
            $path = storage_path() . '/framework/cache/' . $ori_domain;
            if (File::exists($path)) {
                File::deleteDirectory($path);
            }
        }

        //更改域名
        if (isset($params['site_url']) && $params['site_url'] != $old_site_url && empty($old_db_name)) {
            //存入数据库
            $databases_name = $old_site_url;
            $databases_name = str_ireplace('www.', '', $databases_name);
            $databases_name = strtolower($databases_name);
            $databases_name = str_replace('-', '_', $databases_name);
            $databases_name = str_replace('.', '_', $databases_name);
            $params['db_name'] = $databases_name;

        }


        //保存数据
        $data_to_save = $params;

        $data_to_save = $this->app->format->clean_xss($data_to_save);
        if ($siteinfo->validateAndFill($data_to_save)) {
            //1.修改的时候,redis 变化
            $save = $siteinfo->save();
            $filed = $oldsiteinfo['site_url'];
            $val = $oldsiteinfo['conf_dir'];
            $curenvironment = $this->app->environment();
            $domain = $siteinfo['conf_dir'];
            $this->app->detectEnvironment(function () use ($domain) {
                $domain = str_ireplace('www.', '', $domain);
                $domain = strtolower($domain);
                return $domain;
            });

            $directory = \Config::get('cache.stores.file.path') . '/' . $this->app->environment();
            Config::set('webconfig.website.website_title', $siteinfo['site_name']);
            Config::set('webconfig.website.is_publish', $siteinfo['is_publish']);
            if (isset($siteinfo['is_first']) && $siteinfo['is_first'] == 1) {
                Config::set('webconfig.website.is_freeze', $siteinfo['is_freeze']);
            } else {
                Config::set('webconfig.website.is_freeze', 1);
            }
            Config::save('webconfig');

            $userfiles_dir = userfiles_path();

            $hash = md5($this->format_url($domain, true));

            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
            $upload_dir = normalize_path($userfiles_dir . 'cache' . DS . 'thumbnails' . DS);
            $custom_css_path = $userfiles_cache_dir . 'custom_css.' . $hash . '.' . MW_VERSION . '.css';

            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
            $custom_apijs_path = $userfiles_cache_dir . 'api.' . $hash . '.' . MW_VERSION . '.js';


            $site_templates = site_templates();
            $apisetting_arr = array();
            foreach ($site_templates as $tempate_key => $templates) {
                $template_dir = $userfiles_dir . 'templates/' . $templates['dir_name'] . '/';
                $custom_apisettingsjs_path = $userfiles_cache_dir . 'api_settings.' . md5($this->format_url($domain, true) . $template_dir) . '.' . MW_VERSION . '.js';
                if (File::exists($custom_apisettingsjs_path)) {
                    $apisetting_arr[] = $custom_apisettingsjs_path;
                }
            }

            $this->app->detectEnvironment(function () use ($curenvironment) {
                return $curenvironment;
            });

            if (isset($params['site_url']) && $params['site_url'] != $old_site_url) {
                //修改已发布的域名
                if ($old_is_publish == 1) {
                    RedisServer::hdel('domainsmap', $old_site_url);

                }
                RedisServer::hmset('domainsmap', array($params['site_url'] => $params['site_url']));
                $ori_domain = str_ireplace('http://', '', $old_site_url);
                $ori_domain = str_ireplace('https://', '', $ori_domain);
                $ori_domain = str_ireplace('www.', '', $ori_domain);
                $ori_domain = strtolower($ori_domain);
                $path = $this->app->configPath() . '/' . $ori_domain;


                //重命名文件夹
                if (File::exists($path)) {
                    //删除没有用的文件夹
                    if (File::exists($destion_path)) {
                        File::deleteDirectory($destion_path);
                    }
                    File:: move($path, $destion_path);
                    File::deleteDirectory($path);
                } else {
                    return _e('File wroing', true);
                }
            }
            //2.删除的时候，redis变化 删除键值
            if (isset($params['is_delete']) && $params['is_delete'] == 1) {
                RedisServer::hdel('domainsmap', $filed);
                //删除数据库　
                if (empty($old_db_name)) {
                    $databases_name = $val;
                } else {
                    $databases_name = $old_db_name;
                }

                $databases_name = str_ireplace('www.', '', $databases_name);
                $databases_name = strtolower($databases_name);
                $databases_name = str_replace('-', '_', $databases_name);
                $databases_name = str_replace('.', '_', $databases_name);

                $siteinfo = SiteInfo::find(intval($params['id']));
                $siteinfo->is_delete = 1;
                $siteinfo->site_url = $siteinfo->site_url . '-' . date("Y-m-d  h:i:s", time());
                $siteinfo->save();
                DB::connection('mysql-create')->statement("drop database if exists `$databases_name`");
                //删除配置文件
                $this->remove_config($val);
                //删除JS CSS 及缓存文件
                File::exists($custom_css_path) && File::delete($custom_css_path);
                File::exists($custom_apijs_path) && File::delete($custom_apijs_path);
                if (!empty($apisetting_arr)) {
                    foreach ($apisetting_arr as $value) {
                        File::delete($value);
                    }
                }
                !empty($directory) && File::deleteDirectory($directory);
                //删除上传文件
                File::exists($upload_dir) && File::deleteDirectory($upload_dir);//删除所有站点的缩略图（后期优化）
                //删除storge的文件
                $ori_domain = str_ireplace('http://', '', $val);
                $ori_domain = str_ireplace('https://', '', $ori_domain);
                $ori_domain = str_ireplace('www.', '', $ori_domain);
                $ori_domain = strtolower($ori_domain);
                $path = storage_path() . '/framework/cache/' . $ori_domain;
                if (File::exists($path)) {
                    File::deleteDirectory($path);
                }
            }
            if (isset($params['is_publish']) && $params['is_publish'] != $old_is_publish && $params['is_publish'] == 1) {
                if (isset($params['is_publish']) && $params['is_publish'] != $old_is_publish && $params['is_publish'] == 1) {
                    $arr_img = array();
                    $tempate_name = $val;
                    $tempate_name = str_ireplace('http://', '', $tempate_name);
                    $tempate_name = str_ireplace('https://', '', $tempate_name);
                    $tempate_name = str_ireplace('www.', '', $tempate_name);
                    $tempate_name = strtolower($tempate_name);
                    $tempate_name = str_replace('.', '-', $tempate_name);
                    $dir = base_path() . '/userfiles/media/' . $tempate_name . '/uploaded/';
                    $arr_img['dir'] = $dir;
                    $res = mw()->media_manager->create_thumb_byurl($old_site_url, null, $arr_img);
                    //发布的时候重新生成网站缩略图
                    if (isset($res['error'])) {
                        return $res;
                    } else {
                        $siteinfonew = SiteInfo::find($params['id']);
                        $siteinfonew->site_thumb_url = $res['success'];
                        $siteinfonew->save();
                        return $res;
                    }
                }
            }
            //4.取消发布的时候，键值变化 删除键值
            //  if(isset($params['is_publish']) && $params['is_publish']!=$old_is_publish  && $params['is_publish']==0 ){
            //  RedisServer::hdel('domainsmap',$filed);
            // }
        } else {
            $arr['error'] = _e('SiteInfoError1', true);
            return $arr;
        }
        return intval($params['id']);
    }


    public function format_url($domain, $slash = false)
    {
        if (substr($domain, 0, 4) != 'http') {
            @$if_https = $_SERVER['HTTPS'];
            if ($if_https) {
                $domain = 'https://' . $domain;
            } else {
                $domain = 'http://' . $domain;
            }
        }

        if ($slash) {
            $domain = rtrim($domain, '/') . '/';
        }

        return $domain;
    }


    function save_extendinfo($params)
    {
        $data_to_save = $params;
        //判断用户名是否被更改
        /* $user_id=$params['user_id'];
         if(empty($params['user_id'])){
             return 0;
         }else{
             $where['id']= $user_id;
             $user_info = mw()->database_manager->table('users')->where($where)->first();
             if(empty($user_info)){
                 return 0;
             }else{
                 if(!empty($params['username'])){
                     if($user_info->username !=$params['username']){
                         //更新用户名字
                         $userdata['username']=$params['username'];
                         $userdata['id']=$user_id;
                         $save = mw()->database_manager->save('users', $userdata);
                         echo 'sss';die();
                     }
                 }
             }
         }*/

        if (isset($params['id']) and intval($params['id']) != 0) {
            $userextend = UsersExtend::find($params['id']);
        } else {
            $userextend = new UsersExtend();
        }

        //预留银行卡信息
        $data_to_save['bankid'] = '0';

        $data_to_save = $this->app->format->clean_xss($data_to_save);


        if ($userextend->validateAndFill($data_to_save)) {
            $save = $userextend->save();
        } else {
            return array('error' => _e('Error saving the user,check the data format!', true));
        }
        if (isset($params['id']) and intval($params['id']) != 0) {
            $id_to_return = intval($params['id']);
        } else {
            $id_to_return = DB::getPdo()->lastInsertId();
        }
        return array('success' => _e('save success!', true));
    }

    public function reset_password($params)
    {
        if (!isset($params['new_password']) || $params['new_password'] == '') {
            return array('error' => _e('please input the new password', true));
        }
        if (!isset($params['new_repeat_password']) || $params['new_repeat_password'] == '') {
            return array('error' => _e('please repeat input the new password', true));
        }
        if ($params['new_repeat_password'] != $params['new_password']) {
            return array('error' => _e('your new passwords does not match', true));
        }
        if (!preg_match("/^(?![^a-zA-Z]+$)(?!\D+$).{6,16}$/", $params['new_repeat_password'], $matches)) {
            return array('error' => _e('your new password is not legal', true));
        }

        $user_id = user_id();
        $user = User::find($user_id);
        //比较密码是否相同

        $credentials['username'] = $user->username;
        $credentials['password'] = $params['old_password'];

        $credentials_old['username'] = $user->username;
        $credentials_old['password'] = $params['new_password'];
        if (Auth::validate($credentials_old)) {
            return array('error' => _e('oldpassword equaled newpassword', true));
        }
        if (!Auth::validate($credentials)) {
            return array('error' => _e('old password is not correct', true));
        }
        $data_to_save['id'] = $user_id;
        $data_to_save['password'] = $params['new_password'];
        $data_to_save = $this->app->format->clean_xss($data_to_save);
        if ($user->validateAndFill($data_to_save)) {
            $save = $user->save();
            return array('success' => _e('modify password success!', true));
        } else {
            return array('error' => _e('Error saving the user!', true));
        }
    }

    public function save($params)
    {
        if (!isset($params['no_validator'])) {
            $rules = array(
                'email' => 'required|email',
                'username' => 'required|min:4|max:20',
                'password' => 'min:6|max:20',
            );
            $validator = Validator::make($params, $rules);
            if ($validator->fails()) {
                $res['error'] = 'true';
                return $res;
            }
            unset($params['validator']);
        }

        if (isset($params['is_admin']) and $params['is_admin']) {
            // unset($params['is_admin']);
            $data_to_save['table'] = 'admins';
        }

        $force = false;
        if (defined('MW_FORCE_USER_SAVE')) {
            $force = MW_FORCE_USER_SAVE;
        } elseif ($this->force_save) {
            $force = $this->force_save;
        } elseif (mw_var('force_save_user')) {
            $force = mw_var('force_save_user');
        }
        if (!$force) {
            if (defined('MW_API_CALL') and mw_is_installed() == true) {
                if (isset($params['is_admin']) and $this->is_admin() == false and !is_null(User::first())) {
                    unset($params['is_admin']);
                }
            }
        }

        if ($force == false) {
            if (isset($params['id']) and $params['id'] != 0) {
                $adm = $this->is_admin();
                if ($adm == false) {
                    $is_logged = user_id();
                    if ($is_logged == false or $is_logged == 0) {
                        return array('error' => 'You must be logged to save user');
                    } elseif (intval($is_logged) == intval($params['id']) and intval($params['id']) != 0) {
                        // the user is editing their own profile
                    } else {
                        return array('error' => 'You must be logged to as admin save this user');
                    }
                }
            } else {
                if (defined('MW_API_CALL') and mw_is_installed() == true) {
                    $adm = $this->is_admin();
                    if ($adm == false) {
                        $params['id'] = $this->id();
                        $is_logged = user_id();
                        if (intval($params['id']) != 0 and $is_logged != $params['id']) {
                            return array('error' => 'You must be logged save your settings');
                        }
                    } else {
                        if (!isset($params['id'])) {
                            $params['id'] = $this->id();
                        }
                    }
                }
            }
        }

        $data_to_save = $params;

        if (isset($data_to_save['id']) and $data_to_save['id'] != 0 and isset($data_to_save['email']) and $data_to_save['email'] != false) {
            $old_user_data = $this->get_by_id($data_to_save['id']);
            if (isset($old_user_data['email']) and $old_user_data['email'] != false) {
                if ($data_to_save['email'] != $old_user_data['email']) {
                    if (isset($old_user_data['password_reset_hash']) and $old_user_data['password_reset_hash'] != false) {
                        $hash_cache_id = md5(serialize($old_user_data)) . uniqid() . rand();
                        $data_to_save['password_reset_hash'] = $hash_cache_id;
                    }
                }
            }
        }
        if (isset($data_to_save['email']) and isset($data_to_save['id'])) {
            $email = trim($data_to_save['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_existing = array();
                $check_existing['email'] = $email;
                $check_existing['single'] = 1;
                $check_existing = $this->get_all($check_existing);

                if (isset($check_existing['id']) and $check_existing['id'] != $data_to_save['id']) {
                    return array('error' => 'User with this email already exists! Try different email address!');
                }
            }
        }
        if (isset($params['id']) and intval($params['id']) != 0) {
            $user = User::find($params['id']);
        } else {
            $user = new User();
        }   
        
        $id_to_return = false;
        $data_to_save = $this->app->format->clean_xss($data_to_save);

        if ($user->validateAndFill($data_to_save)) {
            $save = $user->save();
            if (isset($user->id)) {
                $data_to_save['id'] = $params['id'] = $user->id;
            }

            if (isset($data_to_save['username']) and $data_to_save['username'] != false and isset($data_to_save['id']) and $data_to_save['id'] != false) {
                $check_existing = array();
                $check_existing['username'] = $data_to_save['username'];
                $check_existing['single'] = 1;
                $check_existing = $this->get_all($check_existing);
                if (isset($check_existing['id']) and $check_existing['id'] != $data_to_save['id']) {
                    return array('error' => 'User with this username already exists! Try different username!');
                }
            }
            
            if (isset($params['attributes']) or isset($params['data_fields'])) {
                $params['extended_save'] = true;
            }

            if (isset($params['extended_save'])) {
                if (isset($data_to_save['password'])) {
                    unset($data_to_save['password']);
                }

                if (isset($data_to_save['id'])) {
                    $data_to_save['table'] = 'users';
                    $this->app->database_manager->extended_save($data_to_save);
                }
            }

            if (isset($params['id']) and intval($params['id']) != 0) {
                $id_to_return = intval($params['id']);
            } else {
                $id_to_return = DB::getPdo()->lastInsertId();
            }
            $params['id'] = $id_to_return;
            $this->app->event_manager->trigger('mw.user.save', $params);
        } else {
            return array('error' => 'Error saving the user!');
        }
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . $id_to_return);
        return $id_to_return;
    }

    public function login_set_failed_attempt()
    {
        $this->app->log_manager->save('title=Failed login&is_system=y&rel_type=login_failed&user_ip=' . MW_USER_IP);
    }

    public function get($params = false)
    {
        $id = $params;
        if ($id == false) {
            $id = $this->id();
        }
        if ($id == 0) {
            return false;
        }
        $res = $this->get_by_id($id);
        if (empty($res)) {
            $res = $this->get_by_username($id);
        }

        return $res;
    }

    public function get_by_email($email)
    {
        $data = array();
        $data['email'] = $email;
        $data['limit'] = 1;
        $data = $this->get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }

        return $data;
    }

    public function get_by_username($username)
    {
        $data = array();
        $data['username'] = $username;
        $data['limit'] = 1;
        $data = $this->get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }

        return $data;
    }

    public function delete($data)
    {
        if (!is_array($data)) {
            $new_data = array();
            $new_data['id'] = intval($data);
            $data = $new_data;
        }
        if (isset($data['id'])) {

            $c_id = intval($data['id']);
            if (isset($data['is_admin']) && $data['is_admin'] == 1) {
                $res = DB::table('role_user')->where('user_id', intval($data['id']))->delete();
                $this->app->database_manager->delete_by_id('admins', $c_id);
            } else {
                $this->app->database_manager->delete_by_id('users', $c_id);
            }
            return $c_id;
        }
        return $data;
    }

    public function reset_password_from_link($params)
    {
        if (!isset($params['captcha'])) {
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $validate_captcha = $this->app->captcha->validate($params['captcha']);
            if (!$validate_captcha) {
                return array('error' => _e('Invalid captcha answer!', true), 'captcha_error' => true);
            }
        }

        if (!isset($params['id']) or trim($params['id']) == '') {
            return array('error' => _e('You must send id parameter', true));
        }

        if (!isset($params['password_reset_hash']) or trim($params['password_reset_hash']) == '') {
            return array('error' => _e('You must send password_reset_hash parameter', true));
        }

        if (!isset($params['pass1']) or trim($params['pass1']) == '') {
            return array('error' => _e('Enter new password!', true));
        }

        if (!isset($params['pass2']) or trim($params['pass2']) == '') {
            return array('error' => _e('Enter repeat new password!', true));
        }

        if ($params['pass1'] != $params['pass2']) {
            return array('error' => _e('Your passwords does not match!', true));
        }
        if (!preg_match("/^(?![^a-zA-Z]+$)(?!\D+$).{6,16}$/", $params['pass1'], $matches)) {
            return array('error' => _e('your new password is not legal', true));
        }
        $data1 = array();
        $data1['id'] = intval($params['id']);
        $data1['password_reset_hash'] = $this->app->database_manager->escape_string($params['password_reset_hash']);
        $table = $this->tables['users'];

        $check = $this->get_all('single=true&password_reset_hash=[not_null]&password_reset_hash=' . $data1['password_reset_hash'] . '&id=' . $data1['id']);
        if (!is_array($check)) {
            return array('error' => _e('Invalid data or expired link!', true));
        } else {
            $data1['password_reset_hash'] = '';
        }
        $this->force_save = true;
        $save = $this->app->database_manager->save($table, $data1);

        $save_user = array();
        $save_user['id'] = intval($params['id']);
        $save_user['password'] = $params['pass1'];
        if (isset($check['email'])) {
            $save_user['email'] = $check['email'];
        }
        $this->app->event_manager->trigger('mw.user.change_password', $save_user);
        $save_user['no_validator'] = 1;
        $this->save($save_user);

        $notif = array();
        $notif['module'] = 'users';
        $notif['rel_type'] = 'users';
        $notif['rel_id'] = $data1['id'];
        $notif['title'] = "The user have successfully changed password. (User id: {$data1['id']})";
        $this->app->log_manager->save($notif);
        $this->session_end();
        return array('success' => _e('Your password have been changed!', true));
    }

    public function session_end()
    {
        \Session::flush();
        \Session::regenerate();
    }

    //后台修改邮箱 第一步
    public function user_send_forgot_email_step1($params)
    {
        //$params['email']='kime@axsoftware.net';
        $rules = array(
            'email' => 'required|email',
        );
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return array('error' => _e('Please input Mail address', true));
        }
        $validate_captcha = $this->app->captcha->make_email_captcha();
        $to = $params['email'];
        $sender = new \Microweber\Utils\MailSender();
        $subject = _e('Email reset!', true);
        $content = _e('hello your captcha is ', true) . $validate_captcha;
        $sender->send($to, $subject, $content);
        return array('success' => _e('Your emmail reset link has been sent to ', true) . $to);
    }

    //后台修改邮箱 第二步
    public function user_send_forgot_email_step2($params)
    {
        if (!isset($params['captcha']) || $params['captcha'] == '') {
            return array('error' => _e('captcha is empty', true));
        }
        $res = $this->app->captcha->email_captcha_validate($params['captcha']);
        if ($res) {
            mw()->user_manager->session_set('captcha_suc', 1);
            return array('success' => _e('captcha susscess', true));
        } else {
            return array('error' => _e('captcha error', true));
        }
    }

    //后台修改邮箱 第三步
    public function user_send_forgot_email_step3($params)
    {
        $rules = array(
            'email' => 'required|email',
        );
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return array('error' => _e('email format woring', true));
        }

        $captcha = mw()->user_manager->session_get('captcha_suc');
        if (empty($captcha) || $captcha == 0) {
            return array('error' => _e('Please get captcha first!', true));
        } else {
            //修改用户邮箱
            $user_id = user_id();
            $user = User::find($user_id);
            if ($user->email == $params['email']) {
                return array('error' => _e('old mailbox equal new mailbox', true));
            }
            $param['email'] = $params['email'];
            $res = $this->check_email($param);
            if (isset($res['error'])) {
                return $res;
            }
            $data_to_save['id'] = $user_id;
            $data_to_save['email'] = $params['email'];
            $data_to_save = $this->app->format->clean_xss($data_to_save);
            if ($user->validateAndFill($data_to_save)) {
                $save = $user->save();
            }
            //mw()->user_manager->session_set('captcha_suc', 0);
            return array('success' => _e("If you have bind new email,use the new email login next time.", true));
        }
    }

    //前台忘记密码第一步
    public function forgot_password_step1($params)
    {
        if (!isset($params['email'])) {
            return array('error' => 'Enter username or email!');
        }
        if (!isset($params['captcha'])) {
            return array('error' => _e('Please enter the captcha answer!'));
        } else {
            $validate_captcha = $this->app->captcha->validate($params['captcha'], null, false);
            if ($validate_captcha == false) {
                return array('error' => _e('Invalid captcha answer!', true), 'captcha_error' => true);
            }
        }
        $validate_captcha = $this->app->captcha->make_email_captcha();
        $to = $params['email'];
        $sender = new \Microweber\Utils\MailSender();
        $subject = _e('Password reset!', true);
        $content = _e('hello your captcha is ', true) . $validate_captcha;
        $sender->send($to, $subject, $content);
        return array('success' => _e('send  captcha successfully!', true));
    }

    //前台忘记密码第二步
    public function forgot_password_step2($params)
    {
        $captcha = mw()->user_manager->session_get('captcha_suc');
        if (empty($captcha) || $captcha == 0) {
            return array('error' => _e('Please get captcha first!', true));
        } else {
            $res = $this->app->captcha->email_captcha_validate($params['captcha']);
            if ($res) {
                //修改用户邮箱
                //  $user_id=user_id();
                //  $captcha=mw()->user_manager->session_get('captcha_suc');
                $where['email'] = $params['email'];
                $user = User::find($where);
                $data_to_save['email'] = $where['email'];
                $data_to_save['password'] = $params['password'];
                $data_to_save = $this->app->format->clean_xss($data_to_save);
                if ($user->validateAndFill($data_to_save)) {
                    $save = $user->save();
                }
                mw()->user_manager->session_set('captcha_suc', 0);
                return array('success' => _e('Modify password successfully!', true));
            } else {
                return array('error' => _e('captcha error', true));
            }
        }
    }

    public function send_forgot_password($params)
    {
        if (!isset($params['captcha'])) {
            return array('error' => _e('Please enter the captcha answer!', true));
        } else {
            $validate_captcha = $this->app->captcha->validate($params['captcha'], null, false);
            if ($validate_captcha == false) {
                return array('error' => _e('Invalid captcha answer!', true), 'captcha_error' => true);
            }
        }
        if (isset($params['email'])) {
            //return array('error' => 'Enter username or email!');
        } elseif (!isset($params['username']) or trim($params['username']) == '') {
            return array('error' => _e('Enter username or email!', true));
        }

        $data_res = false;
        $data = false;
        if (isset($params) and !empty($params)) {
            $user = isset($params['username']) ? $params['username'] : false;
            $email = isset($params['email']) ? $params['email'] : false;
            $data = array();
            if (trim($user != '')) {
                $data1 = array();
                $data1['username'] = $user;
                $data = array();
                if (trim($user != '')) {
                    $data = $this->get_all($data1);
                    if ($data == false) {
                        $data1 = array();
                        $data1['email'] = $user;
                        $data = $this->get_all($data1);
                    }
                }
            } elseif (trim($email != '')) {
                $data1 = array();
                $data1['email'] = $email;
                $data = array();
                if (trim($email != '')) {
                    $data = $this->get_all($data1);
                }
            }

            if (isset($data[0])) {
                $data_res = $data[0];
            }
            if (!is_array($data_res)) {
                return array('error' => _e('Enter right username or email!', true));
            } else {
                $to = $data_res['email'];
                if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                    $subject = 'Password reset!';
                    $content = "Hello, {$data_res['username']} <br> ";
                    $content .= _e('you have requested', true) . MW_USER_IP . '<br><br> ';
                    $security = array();
                    $security['ip'] = MW_USER_IP;
                    //  $security['hash'] = $this->app->format->array_to_base64($data_res);
                    // $function_cache_id = md5(rand()) . uniqid() . rand() . str_random(40);
                    $function_cache_id = md5($data_res['id']) . uniqid() . rand() . str_random(40);
                    if (isset($data_res['id'])) {
                        $data_to_save = array();
                        $data_to_save['id'] = $data_res['id'];
                        $data_to_save['password_reset_hash'] = $function_cache_id;
                        $table = $this->tables['users'];
                        $save = $this->app->database_manager->save($table, $data_to_save);
                    }

                    $base_link = $this->app->url_manager->current(1);

                    $cur_template = template_dir();
                    $cur_template_file = normalize_path($cur_template . 'login.php', false);
                    $cur_template_file2 = normalize_path($cur_template . 'forgot_password.php', false);
                    if (is_file($cur_template_file)) {
                        $base_link = site_url('login');
                    } elseif (is_file($cur_template_file2)) {
                        $base_link = site_url('forgot_password');
                    }

                    $pass_reset_link = $base_link . '?reset_password_link=' . $function_cache_id;
                    $security['base_link'] = $base_link;
                    $security['reset_password_link'] = "<a href='{$pass_reset_link}'>" . $pass_reset_link . '</a>';
                    $security['username'] = $data_res['username'];
                    $security['first_name'] = $data_res['first_name'];
                    $security['last_name'] = $data_res['last_name'];
                    $security['created_at'] = $data_res['created_at'];
                    $security['email'] = $data_res['email'];
                    $security['id'] = $data_res['id'];

                    $notif = array();
                    $notif['module'] = 'users';
                    $notif['rel_type'] = 'users';
                    $notif['rel_id'] = $data_to_save['id'];
                    $notif['title'] = 'Password reset link sent';
                    $content_notif = "User with id: {$data_to_save['id']} and email: {$to}  has requested a password reset link";
                    $notif['description'] = $content_notif;
                    $this->app->log_manager->save($notif);
                    $content .= _e('click here to reset your password', true) . "<a style='word-break:break-all;' href='{$pass_reset_link}'>" . $pass_reset_link . '</a><br><br> ';

                    //custom email

                    if (get_option('forgot_pass_email_enabled', 'users')) {
                        $cust_subject = get_option('forgot_pass_email_subject', 'users');
                        $cust_content = get_option('forgot_pass_email_content', 'users');
                        if (trim($cust_subject) != '') {
                            $subject = $cust_subject;
                        }
                        if ($cust_content != false) {
                            $cust_content_check = strip_tags($cust_content);
                            $cust_content_check = trim($cust_content_check);
                            if ($cust_content_check != '') {
                                foreach ($security as $key => $value) {
                                    if (!is_array($value) and is_string($key)) {
                                        $cust_content = str_ireplace('{' . $key . '}', $value, $cust_content);
                                    }
                                }
                                $content = $cust_content;
                            }
                        }
                    }
                    $sender = new \Microweber\Utils\MailSender();
                    $sender->send($to, $subject, $content);

                    return array('success' => _e('Your password reset link has been sent to ', true) . $to);
                } else {
                    return array('error' => _e('Error: the user doesn\'t have a valid email address!', true));
                }
            }
        }
    }

    public function social_login($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $return_after_login = false;
        if (isset($params['redirect'])) {
            $return_after_login = $params['redirect'];
            $this->session_set('user_after_login', $return_after_login);
        } elseif (isset($_SERVER['HTTP_REFERER']) and stristr($_SERVER['HTTP_REFERER'], $this->app->url_manager->site())) {
            $return_after_login = $_SERVER['HTTP_REFERER'];
            $this->session_set('user_after_login', $return_after_login);
        }
        $provider = false;
        if (isset($_REQUEST['provider'])) {
            $provider = $_REQUEST['provider'];
            $provider = trim(strip_tags($provider));
        }
        if ($provider != false and isset($params) and !empty($params)) {
            $this->socialite_config($provider);
            switch ($provider) {
                case 'github':
                    return $login = $this->socialite->with($provider)->scopes(['user:email'])->redirect();
            }

            return $login = $this->socialite->with($provider)->redirect();
        }
    }

    public function make_logged($user_id)
    {
        if (is_array($user_id)) {
            if (isset($user_id['id'])) {
                $user_id = $user_id['id'];
            }
        }
        if (intval($user_id) > 0) {

            $data = $this->get_by_id($user_id);

            if ($data == false) {
                return false;
            } else {
                if (is_array($data)) {
                    $user_session = array();
                    $user_session['is_logged'] = 'yes';
                    $user_session['user_id'] = $data['id'];

                    if (!defined('USER_ID')) {
                        define('USER_ID', $data['id']);
                    }

                    $old_sid = Session::getId();
                    $data['old_sid'] = $old_sid;
                    $user_session['old_session_id'] = $old_sid;
                    $current_user = Auth::user();
                    if ((isset($current_user->id) and $current_user->id == $user_id)) {
                        Auth::login(Auth::user());
                    } else {
                        Auth::loginUsingId($data['id']);
                    }
//
//                    Session::setId($old_sid);
//                    Session::save();

                    $this->app->event_manager->trigger('mw.user.login', $data);
                    $this->session_set('user_session', $user_session);
                    $user_session = $this->session_get('user_session');

                    $this->update_last_login_time();
                    $user_session['success'] = _e('You are logged in!', true);

                    return $user_session;
                }
            }
        }
    }

    /**
     * Generic function to get the user by id.
     * Uses the getUsers function to get the data.
     *
     * @param
     *            int id
     *
     * @return array
     */
    public function getadmin_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        $data = array();
        $data['id'] = $id;
        $res = $this->app->database_manager->get('admins', $data);
        return $res[0];
    }

    /**
     * Generic function to get the user by id.
     * Uses the getUsers function to get the data.
     *
     * @param
     *            int id
     *
     * @return array
     */
    public function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        $data = array();
        $data['id'] = $id;
        $res = $this->app->database_manager->get('users', $data);
        return $res[0];
    }

    /*
     * colby 用户详情页 获得网站相关信息
     * */
    public function get_siteinfo_by_id($id = null)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $res = SiteInfo::where(['user_id' => $id, 'is_delete' => 0])->get()->toArray();
        return $res;
    }

    /*
     * colby 用户详情页 获得网站相关信息
     * */
    public function get_userinfo_by_id($id = null)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        if ($num = UsersExtend::where(['user_id' => $id])->count()) {
            $userinfo = UsersExtend::where(['user_id' => $id])->orderBy('created_at', 'desc')->first()->toArray();
            return $userinfo;
        } else {
            return [];
        }
    }

    /**
     * Generic function to get the admin user by id.
     * Uses the getUsers function to get the data.
     *
     * @param
     *            int id
     *
     * @return array
     */
    public function get_admin_id($id)
    {

        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        $data = array();
        $data['id'] = $id;
        $res = $this->app->database_manager->get('admins', $data);
        return $res;
    }

    public function update_last_login_time()
    {
        $uid = user_id();
        if (intval($uid) > 0) {
            $data_to_save = array();
            $data_to_save['id'] = $uid;
            $data_to_save['last_login'] = date('Y-m-d H:i:s');
            $data_to_save['last_login_ip'] = MW_USER_IP;
            $table = $this->tables['users'];
            $save = $this->app->database_manager->save($table, $data_to_save);
            $this->app->log_manager->delete('is_system=y&rel_type=login_failed&user_ip=' . MW_USER_IP);
        }
    }

    public function social_login_process($params = false)
    {
        $user_after_login = $this->session_get('user_after_login');

        if (!isset($_REQUEST['provider']) and isset($_REQUEST['hauth_done'])) {
            $_REQUEST['provider'] = $_REQUEST['hauth_done'];
        }
        if (!isset($_REQUEST['provider'])) {
            return $this->app->url_manager->redirect(site_url());
        }

        $auth_provider = $_REQUEST['provider'];

        try {
            $this->socialite_config($auth_provider);
            $user = $this->socialite->driver($auth_provider)->user();
            $email = $user->getEmail();

            $username = $user->getNickname();
            $oauth_id = $user->getId();
            $avatar = $user->getAvatar();
            $name = $user->getName();

            $existing = array();

            if ($email != false) {
                $existing['email'] = $email;
            } else {
                $existing['oauth_uid'] = $oauth_id;
                $existing['oauth_provider'] = $auth_provider;
            }
            $save = $existing;
            $save['thumbnail'] = $avatar;
            $save['username'] = $username;
            $save['is_active'] = 1;
            $save['is_admin'] = is_null(User::first());
            $save['first_name'] = '';
            $save['last_name'] = '';
            if ($name != false) {
                $names = explode(' ', $name);
                if (isset($names[0])) {
                    $save['first_name'] = array_shift($names);
                    if (!empty($names)) {
                        $last = implode(' ', $names);
                        $save['last_name'] = $last;
                    }
                }
            }
            $existing['single'] = true;
            $existing['limit'] = 1;
            $existing = $this->get_all($existing);
            if (!defined('MW_FORCE_USER_SAVE')) {
                define('MW_FORCE_USER_SAVE', true);
            }
            if (isset($existing['id'])) {
                if ($save['is_active'] != 1) {
                    return;
                }
                $this->make_logged($existing['id']);
            } else {
                $new_user = $this->save($save);
                $this->after_register($new_user);

                $this->make_logged($new_user);
            }
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            print_r($e->getMessage());
            //do nothing
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            print_r($e->getMessage());
            //do nothing
        } catch (\InvalidArgumentException $e) {
            print_r($e->getMessage());
            //do nothing
        } catch (\Exception $e) {
            print_r($e->getMessage());
            //do nothing
        }

        if ($user_after_login != false) {
            return $this->app->url_manager->redirect($user_after_login);
        } else {
            return $this->app->url_manager->redirect(site_url());
        }
    }

    public function count()
    {
        $options = array();
        $options['count'] = true;
        $options['cache_group'] = 'users/global/';
        $data = $this->get_all($options);

        return $data;
    }

    /**
     * @function get_users
     *
     * @param $params array|string;
     * @params $params['username'] string username for user
     * @params $params['email'] string email for user
     * @params $params['password'] string password for user
     * @usage $this->get_all('email=my_email');
     *
     * @return array of users;
     */
    public function get_all($params)
    {

        $params = parse_params($params);
        if (isset($params['is_admin']) && $params['is_admin'] == 1) {
            unset($params['is_admin']);
            $table = 'admins';
        } else {
            $table = $this->tables['users'];
        }
        $data = $this->app->format->clean_html($params);
        $orig_data = $data;

        if (isset($data['ids']) and is_array($data['ids'])) {
            if (!empty($data['ids'])) {
                $ids = $data['ids'];
            }
        }
        if (!isset($params['search_in_fields'])) {
            $data['search_in_fields'] = array('id', 'first_name', 'last_name', 'username', 'email');
        }
        $cache_group = 'users/global';
        if (isset($limit) and $limit != false) {
            $data['limit'] = $limit;
        }
        if (isset($count_only) and $count_only != false) {
            $data['count'] = $count_only;
        }
        if (isset($data['username']) and $data['username'] == false) {
            unset($data['username']);
        }
        $data['table'] = $table;
        $get = $this->app->database_manager->get($data);
        return $get;
    }

    public function register_url()
    {
        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'register.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'register';
        } else {
            $default_url = 'users/register';
        }

        $checkout_url = $this->app->option_manager->get('register_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }
        $checkout_url_sess = $this->session_get('register_url');
        if ($checkout_url_sess == false) {
            return $this->app->url_manager->site($default_url);
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }
    }

    public function logout_url()
    {
        return api_url('logout');
    }

    public function login_url()
    {
        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'login.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'login';
        } else {
            $default_url = 'users/login';
        }

        $checkout_url = $this->app->option_manager->get('login_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('login_url');

        if ($checkout_url_sess == false) {
            return $this->app->url_manager->site($default_url);
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }
    }

    public function forgot_password_url()
    {
        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'forgot_password.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'forgot_password';
        } else {
            $default_url = 'users/forgot_password';
        }
        $checkout_url = $this->app->option_manager->get('forgot_password_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }
        $checkout_url_sess = $this->session_get('forgot_password_url');
        if ($checkout_url_sess == false) {
            return $this->app->url_manager->site($default_url);
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }
    }

    public function session_set($name, $val)
    {
        $this->app->event_manager->trigger('mw.user.session_set', $name, $val);

        return Session::put($name, $val);
    }

    public function csrf_form($unique_form_name = false)
    {
        if ($unique_form_name == false) {
            $unique_form_name = uniqid();
        }

        $token = $this->csrf_token($unique_form_name);

        $input = '<input type="hidden" value="' . $token . '" name="_token">';

        return $input;
    }

    public function session_all()
    {
        $value = Session::all();
        return $value;
    }

    public function session_id()
    {
        return Session::getId();
    }

    public function session_get($name)
    {
        $value = Session::get($name);

        return $value;
    }

    public function session_del($name)
    {
        Session::forget($name);
    }

    public function csrf_token($unique_form_name = false)
    {
        return csrf_token();
    }

    public function site_catagies()
    {
        echo 0;
        die();
    }

    public function socialite_config($provider = false)
    {
        $callback_url = api_url('social_login_process?provider=' . $provider);

        if (get_option('enable_user_fb_registration', 'users') == 'y') {
            Config::set('services.facebook.client_id', get_option('fb_app_id', 'users'));
            Config::set('services.facebook.client_secret', get_option('fb_app_secret', 'users'));
            Config::set('services.facebook.redirect', $callback_url);
        }

        if (get_option('enable_user_twitter_registration', 'users') == 'y') {
            Config::set('services.twitter.client_id', get_option('twitter_app_id', 'users'));
            Config::set('services.twitter.client_secret', get_option('twitter_app_secret', 'users'));
            Config::set('services.twitter.redirect', $callback_url);
        }

        if (get_option('enable_user_google_registration', 'users') == 'y') {
            Config::set('services.google.client_id', get_option('google_app_id', 'users'));
            Config::set('services.google.client_secret', get_option('google_app_secret', 'users'));
            Config::set('services.google.redirect', $callback_url);
        }

        if (get_option('enable_user_github_registration', 'users') == 'y') {
            Config::set('services.github.client_id', get_option('github_app_id', 'users'));
            Config::set('services.github.client_secret', get_option('github_app_secret', 'users'));
            Config::set('services.github.redirect', $callback_url);
        }

        if (get_option('enable_user_linkedin_registration', 'users') == 'y') {
            Config::set('services.linkedin.client_id', get_option('linkedin_app_id', 'users'));
            Config::set('services.linkedin.client_secret', get_option('linkedin_app_secret', 'users'));
            Config::set('services.linkedin.redirect', $callback_url);
        }

        if (get_option('enable_user_gocms_registration', 'users') == 'y') {
            $svc = Config::get('services.gocms');


            /* if (!isset($svc['client_id'])) {
                 Config::set('services.gocms.client_id', get_option('gocms_app_id', 'users'));
             }
             if (!isset($svc['client_secret'])) {
                 Config::set('services.gocms.client_secret', get_option('gocms_app_secret', 'users'));
             }*/
            if (!isset($svc['redirect'])) {
                Config::set('services.gocms.redirect', $callback_url);
            }
            $this->socialite->extend('gocms', function ($app) {
                $config = $app['config']['services.gocms'];
                return $this->socialite->buildProvider('\Microweber\Providers\Socialite\MicroweberProvider', $config);
            });
        }
    }

    public function get_user_statistics($params = null, $isWeek = null)
    {
        $result = [];
        if ($params == null) {
            $startTime = date("Y-m-d h:i:s", strtotime("-1 week"));
            $endTime = date('Y-m-d h:i:s', time());
            $result['date_period'] = date("Y/m/d", strtotime("-1 week")) . '-' . date('Y/m/d', time());;
        } else {
            $startTime = date("Y-m-d h:i:s", strtotime('-7 days', strtotime($params)));
            $endTime = date('y-m-d h:i:s', strtotime($params));
        }
        if ($isWeek == true) {
            $data = Statistics::whereBetween('created_at', [$startTime, $endTime])->get()->toArray();
            if ($data) {
                $userNumber = 0;
                $userTotal = 0;
                $siteNumber = 0;
                $siteTotal = 0;
                $siteProportion = 0;
                $publicTotal = 0;

                if (is_array($data) && count($data) > 1) {
                    foreach ($data as $key => $item) {
                        $userNumber += $item['user_number'];
                        $siteNumber += $item['site_number'];
                        $siteProportion += $item['site_proportion'];
                        $publicTotal += $item['public_total'];
                    }
                    $result['created_at'] = date('y-m-d h:i:s', time());
                    $result['user_number'] = $userNumber;
                    $result['user_total'] = $countTotal = User::all()->count();;
                    $result['site_number'] = $siteNumber;
                    $result['site_total'] = SiteInfo::all()->count();
                    $result['site_proportion'] = $siteProportion;
                    $result['public_total'] = $publicTotal;
                    $result['is_week'] = 1;

                    $save = $this->app->database_manager->save("statistics", $result);
                }
            }
        } else {
            $datas = Statistics::where(['is_week' => 1])->get(['date_period', 'user_number', 'user_total', 'site_number',
                'site_total', 'site_proportion', 'public_total'
            ])->toArray();
            $siteNumbers = DB::select('select master_id,count(*) as count from cms_site_infos group by master_id having count>0 ORDER BY count DESC');
            $i = 0;
            if ($siteNumbers) {
                foreach ($siteNumbers as $siteNumber) {
                    $templateName = Templates::where('id', $siteNumber->master_id)->get(['name'])->toArray();
                    if (!empty($templateName)) {
                        $nullArray[] = $templateName[0]['name'];
                        $i++;
                        if ($i == 3) {
                            break;
                        }
                    }

                }
            }
            if (!empty($datas)) {
                $num = count($datas);
                for ($k = 0; $k < $num; $k++) {
                    if (!empty($nullArray)) {
                        $datas[$k]['site_more'] = implode(',', $nullArray);
                    } else {
                        $datas[$k]['site_more'] = null;
                    }
                }
                return $datas;
            } else {
                return [];
            }


        }


    }

    public function save_user_statistics()
    {

        $time = time();
        $startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1);
        $userCount = User::whereBetween('created_at', [$startTime, $endTime])->count();
        $countTotal = User::all()->count();
        $siteCount = SiteInfo::whereBetween('created_at', [$startTime, $endTime])->count();
        $siteTotal = SiteInfo::all()->count();
        $publicTotal = SiteInfo::whereBetween('created_at', [$startTime, $endTime])->where(['is_publish' => 1])->count();
        $allUrl = SiteInfo::all(['site_url'])->toArray();
        $t = 0;
        foreach ($allUrl as $url) {
            $urlArray = explode('.', $url['site_url']);
            if ($urlArray[1] == 'cms' || $urlArray[1] == 'masterbuildsite') {
                continue;
            } else {
                $t++;
            }
        }

        $data_to_save['created_at'] = date('y-m-d h:i:s', $time);
        $data_to_save['user_number'] = $userCount;
        $data_to_save['user_total'] = $countTotal;
        $data_to_save['site_number'] = $siteCount;
        $data_to_save['site_total'] = $siteTotal;
        $data_to_save['public_total'] = $publicTotal;
        $data_to_save['site_proportion'] = round((($t / $siteTotal) * 100), 2);
        $save = $this->app->database_manager->save("statistics", $data_to_save);
        $number = Statistics::where(['is_week' => 0])->count();
        if ($number % 7 == 0) {
            get_user_statistics(null, true);
        }
    }

    public function get_use_ip()
    {
        foreach (array('HTTP_X_FORWARDED_FOR',
                     'HTTP_CLIENT_IP',
                     'HTTP_X_CLIENT_IP',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){

                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress);
                    if (filter_var($IPaddress, FILTER_VALIDATE_IP) !== false) {

                        return $IPaddress;
                    }
                }
            }
        }


    }


    public function detection_public_time()
    {
        $publicTime = SiteInfo::where(['is_publish' => 0])->get(['id', 'site_url', 'public_time'])->toArray();
        if(is_array($publicTime) && count($publicTime) > 0) {
            foreach ($publicTime as $item) {
                if($item['public_time']){
                    $startdate= $item['public_time'];
                    $enddate= date('Y-m-d h:i:s', time());;
                    $upperTime = sprintf("%.2f",(strtotime($enddate)-strtotime($startdate))/86400);
                    if($upperTime >= 30) {
                        $filed =  $item['site_url'];
                        RedisServer::hdel('domainsmap', $filed);
                        //删除数据库　

                        $databases_name = $filed;


                        $databases_name = str_ireplace('www.', '', $databases_name);
                        $databases_name = strtolower($databases_name);
                        $databases_name = str_replace('-', '_', $databases_name);
                        $databases_name = str_replace('.', '_', $databases_name);
                        SiteInfo::where(['id'=>$item['id']])->delete();

                        DB::connection('mysql-create')->statement("drop database if exists `$databases_name`");
                        //删除配置文件
                        $this->remove_config($filed);
                        //删除JS CSS 及缓存文件
                        $userfiles_dir = userfiles_path();

                        $hash = md5($this->format_url($filed, true));

                        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
                        $upload_dir = normalize_path($userfiles_dir . 'cache' . DS . 'thumbnails' . DS);
                        $custom_css_path = $userfiles_cache_dir . 'custom_css.' . $hash . '.' . MW_VERSION . '.css';

                        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
                        $custom_apijs_path = $userfiles_cache_dir . 'api.' . $hash . '.' . MW_VERSION . '.js';
                        File::exists($custom_css_path) && File::delete($custom_css_path);
                        File::exists($custom_apijs_path) && File::delete($custom_apijs_path);
                        if (!empty($apisetting_arr)) {
                            foreach ($apisetting_arr as $value) {
                                File::delete($value);
                            }
                        }
                        !empty($directory) && File::deleteDirectory($directory);
                        //删除上传文件
                        File::exists($upload_dir) && File::deleteDirectory($upload_dir);//删除所有站点的缩略图（后期优化）
                        //删除storge的文件
                        $ori_domain = str_ireplace('http://', '', $filed);
                        $ori_domain = str_ireplace('https://', '', $ori_domain);
                        $ori_domain = str_ireplace('www.', '', $ori_domain);
                        $ori_domain = strtolower($ori_domain);
                        $path = storage_path() . '/framework/cache/' . $ori_domain;
                        if (File::exists($path)) {
                            File::deleteDirectory($path);
                        }
                    }elseif ($upperTime >= 15 && $upperTime <= 30) {
                        SiteInfo::where(['id'=>$item['id']])->update(['is_delete' => 1]);
                    }else {
                        continue;
                    }
                }
            }
        }
    }
    public function file_capacity($siteUrl)
    {
        $dir_size = 0;
        $userfiles_dir = userfiles_path();
        $siteUrl = str_ireplace('http://', '', $siteUrl);
        $siteUrl = str_ireplace('https://', '', $siteUrl);
        $siteUrl='http://'.$siteUrl;
        $urlName = str_replace('.', '-', explode('/', $siteUrl)[2]);
        $directory = normalize_path($userfiles_dir . 'media' . DS . $urlName . DS . 'uploaded' . DS);
        if(is_dir($directory)) {
            if ($dir_handle = @opendir($directory)) {
                while ($filename = readdir($dir_handle)) {
                    if ($filename != "." && $filename != "..") {
                        $subFile = $directory . "/" . $filename;
                        if (is_dir($subFile))
                            $dir_size += dirSize($subFile);
                        if (is_file($subFile))
                            $dir_size += filesize($subFile);
                    }
                }
                closedir($dir_handle);
                return round($dir_size / 1048576, 2);
            }
        } else {
            return 0;
        }
    }
    public function get_disk_capacity($siteUrl)
    {
        try{
            $siteUrl = str_ireplace('http://', '', $siteUrl);
            $siteUrl = str_ireplace('https://', '', $siteUrl);
            $siteUrl='http://'.$siteUrl;
            $siteUrl = str_replace('.', '_', explode('/', $siteUrl)[2]);
            $database = DB::select("SELECT sum(DATA_LENGTH)+sum(INDEX_LENGTH) as total FROM information_schema.TABLES where TABLE_SCHEMA='{$siteUrl}'");
            if($database) {
                $capacity = sprintf("%.2f", current($database)->total/1048576);
                return $capacity;
            }
            return 0;
        }catch (Exception $exception) {
            return;
        }


    }
}
