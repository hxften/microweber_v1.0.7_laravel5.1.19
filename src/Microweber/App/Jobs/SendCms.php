<?php

namespace Microweber\App\Jobs;

use Microweber\App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

//microweber_v1.0.9_laravel5.4.17 的
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use log;
use Microweber\SendCms1;
use App\UsersSms;

class SendCms extends Job implements SelfHandling
{
    
    protected $user;
    protected $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$email)
    {
        //
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        //sleep(5);
        //$this->release(10);
        if ($this->attempts() > 3) {
            \Log::info($this->user.'邮件参试失败过多');
        }else{ 
            // 每次进来休息3秒钟
            sleep(3);
            // 休息10秒钟
            //$this->release(10);

            $data['status'] = 'sucess';
            $data = array(
                'username' => $this->user,
                'email' => $this->email,
                'areacode' => '0086',
                'phone' => '13261352452',
                'taskid' => '55552',
                'lang' => 'zh-cn',
                'message' => '发送成功',
                'content' => '恭喜',
                'verification_code' => '验证码',
            );
            if($data){
                $data['status'] = '1';
                \Log::info($this->user.'邮件发送成功');
            }else{
                $data['status'] = '0';
                \Log::info($this->user.'邮件发送失败');
            }

            //$result = UsersSms::create($data);
            $UsersSms = new UsersSms($data);
            $result = $UsersSms->save();
            echo '<pre>';
            var_Dump($result);
        }

    }

    /**
     * 处理一个失败的任务
     *
     * @return void
     */
    public function failed()
    {
        \Log::error($this->user.'队列任务执行失败'."\n".date('Y-m-d H:i:s'));
    }
}
