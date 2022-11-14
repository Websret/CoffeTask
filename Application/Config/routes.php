<?php

return [
    '^$' => 'main/index',
    '^main/update/([0-9]+)$' => 'main/update/$1',
    '^main/change' => 'main/change',
    '^main/delete/([0-9]+)$' => 'main/delete/$1',
    '^main/add$' => 'main/add',
    '^main/create$' => 'main/create',
    '^user/registration$' => 'user/registration',
    '^user/registry$' => 'user/registry',
    '^user/login$' => 'user/login',
    '^user/authorization$' => 'user/authorization',
    '^user/logout$' => 'user/logout',
    '^product/add$' => 'product/add',
    '^product/create$' => 'product/create',
    '^client/add$' => 'client/add',
    '^client/create$' => 'client/create',
    '^department/add$' => 'department/add',
    '^department/create$' => 'department/create',
];
