<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * Get authenticated user safely
     */
    public static function user()
    {
        return Auth::user();
    }

    /**
     * Get user name safely
     */
    public static function userName()
    {
        $user = Auth::user();
        return $user ? $user->name : 'Guest';
    }

    /**
     * Get user email safely
     */
    public static function userEmail()
    {
        $user = Auth::user();
        return $user ? $user->email : '';
    }

    /**
     * Get user initials safely
     */
    public static function userInitials()
    {
        $user = Auth::user();
        if (!$user) {
            return 'G';
        }
        
        $name = $user->name;
        $first = substr($name, 0, 1);
        $second = strstr($name, ' ') ? substr(strstr($name, ' '), 1, 1) : '';
        
        return strtoupper($first . $second);
    }

    /**
     * Check if user is authenticated
     */
    public static function check()
    {
        return Auth::check();
    }

    /**
     * Get user role safely
     */
    public static function userRole()
    {
        $user = Auth::user();
        return $user ? $user->role : 'guest';
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }
} 