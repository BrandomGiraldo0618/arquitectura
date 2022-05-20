<?php

namespace App\Library;

use App\User;
use Auth;
use DB;
use Mail;
use View;


class General
{
	/**
	 * @return string
	 */
	public static function tokenGenerate()
    {
        //Create new file name
        $characters = 'ABCDEFHJKLMNPQRTUWXYZABCDEFHJKLMNPQRTUWXYZ1234567890';
        $unique = substr(str_shuffle($characters), 0, 20);
        //return Hash::make($unique);
        return $unique;
    }

    public static function removeAccents($string)
    {
        $originals = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $updated = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($originals), $updated);
        return utf8_encode($string);
    }

    /**
     * Generates an unique file name
     * @param  string $extension
     * @return string
     */
    public static function fileName($extension = '')
    {
        //Create new file name
        $characters = 'ABCDEFHJKLMNPQRTUWXYZABCDEFHJKLMNPQRTUWXYZ';
        $unique = substr(str_shuffle($characters), 0, 10);
        $epoch = date('YmdHis');
        $fileName = $unique . '_' . $epoch . '.' . $extension;
        return $fileName;
    }

    public static function sendMail($email, $subject, $template, $data)
    {
        $to = $email;
        $fromEmail = ENV('MAIL_FROM_ADDRESS');
        $fromName = ENV('MAIL_FROM_NAME');

        Mail::send('emails.'.$template, $data, function($message) use ($to, $subject, $fromEmail, $fromName) {
            $message->from($fromEmail, $fromName);
            $message->subject($subject);
            $message->to($to);
        });

        return true;
    }

    public static function curl($url,$method,$data = [],$httpHeaders = [])
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if('POST' == $method)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 12000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

        return $result;
    }

    public static function active($value)
    {
        $active = 'Activo';

        if(0 == $value)
        {
            $active = 'Inactivo';
        }

        return $active;
    }

    public static function isPublic($value)
    {
        $isPublic = 'Si';

        if(0 == $value)
        {
            $isPublic = 'No';
        }

        return $isPublic;
    }
}
