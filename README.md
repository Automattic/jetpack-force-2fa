# jetpack-force-2fa

Deprecated. This functionality now ships within Jetpack itself. See https://github.com/Automattic/jetpack/blob/trunk/projects/packages/connection/src/sso/class-force-2fa.php

When using a modern version of Jetpack, you only need to use the `jetpack_force_2fa` filter ([docs](https://developer.jetpack.com/hooks/jetpack_force_2fa/)). This will force 2FA for admin users (`manage_options` cap). You can modify this via the `jetpack_force_2fa_cap` filter ([docs](https://developer.jetpack.com/hooks/jetpack_force_2fa_cap/)). [Other filters are available](https://developer.jetpack.com/module/sso/).
