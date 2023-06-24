<?php

/* Files that starts with underscore or dot will not be executed */

err_log( pathinfo( __FILE__, PATHINFO_BASENAME ) . ' included' );
