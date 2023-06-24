<?php

/* Files with underscore or dot at begining will not be executed */

err_log( pathinfo( __FILE__, PATHINFO_BASENAME ) . ' included' );
