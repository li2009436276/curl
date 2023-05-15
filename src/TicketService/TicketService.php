<?php
/**
 * Created by PhpStorm.
 * User: lmm
 * Date: 2019/4/24
 * Time: 18:24
 */

namespace Curl\TicketService;

use Illuminate\Support\Facades\Cache;
use MC;
class TicketService
{
    /**
     * 获取ticket
     * 
     */ 
    public static function createTicket($data,$isAdmin = false){

        if ($isAdmin) {
            
            $data['is_admin'] = 1;
        }

        unset($data['pwd'],$data['password'],$data['salt']);

        $ticket = str_random(32);

        Cache::put($ticket,$data,env('SESSION_LIFETIME'));

        return $ticket;
    }

    /**
     * 获取ticket的值
     * 
     */ 
    public static function getTicket($ticket){

        return Cache::get($ticket);
    }

    /**
     * 删除ticket
     * @param $ticket
     * @return void
     */
    public static function deleteTicket($ticket){

        Cache::forget($ticket);
    }
}