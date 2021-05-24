<?php

function isNotAdminRole($role){
    if('SuperAdmin' === $role) return false;
    if('Admin' === $role) return false;
    return true;
}
