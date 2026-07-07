<?php

namespace App;

enum Roles: string
{
    case Super_Admin = 'super_admin';
    case Admin = 'admin';
    case Viewer = 'viewer';
}
