<?php
/**
 *    Cookie Monster Class
 * Handles setting, detection and desctruction of cookies
 *
 * nom nom nom
 *            \   _  _
 *              _/0\/ \_
 *     .-.   .-` \_/\0/ '-.
 *    /:::\ / ,_________,  \
 *   /\:::/ \  '. (:::/  `'-;
 *   \ `-'`\ '._ `"'"'\__    \
 *    `'-.  \   `)-=-=(  `,   |
 *        \  `-"`      `"-`   /
 *
 * @version 01
 * @author Stephen McMahon <stephentmcm@gmail.com>
 */
class CookieMonster
{
    private $user;

    public function __construct()
    {
    }

    public function setDatabase(PDO $database)
    {
        $this->user = new User($database);
    }

    public function lookForCookies()
    {
        //Found Any cookies?
        if (!empty($_COOKIE['cookie'])) {

            $cookie = $_COOKIE['cookie'];

            if ($this->eatCookie($cookie)) {
                echo'om nom nom nom';
                //User model tries to log in and will return the result
                return($this->user->automaticLogin($cookie['username']));

            }
        }
    }

    private function eatCookie($cookie)
    {
        $lastLogin = $this->user->getlastLogin($cookie['username']);

        $hash = $this->bakeCookie($cookie['username'],$lastLogin);

        echo 'nhash:= '.$hash.'<br>';
        echo 'chash:- '.$cookie['hash'];
        if ($hash === $cookie['hash']) {
            return true;
        } else {
            return false;
        }

    }

    public function giveCookie(User $user)
    {
        $hash = $this->bakeCookie($user->getUsername(),$user->getlastLogin());

        $oneMonthFromNow = time() + (30 * 7 * 24 * 60 * 60);

        // set the cookies
        setcookie("cookie[username]", $user->getUsername(), $oneMonthFromNow);

        setcookie("cookie[hash]", $hash, $oneMonthFromNow);

    }

    public function bakeCookie($username, $time)
    {
        return($username.' - '.$time);
    }

    public function smashCookies(User $user)
    {
        //Set time to past to expire the cookie
        $pastTime = time() - 500;

        // set the cookies
        setcookie("cookie[username]", $user->getUsername(), $pastTime);

        setcookie("cookie[hash]", null, $pastTime);

    }
}
