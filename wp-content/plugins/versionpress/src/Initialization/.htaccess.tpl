# Apache 2.2
<IfModule !mod_authz_core.c>
    Order Deny,Allow
    Deny from all
</IfModule>

# Apache 2.4
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
