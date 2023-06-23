<?php

/* Files with underscore at begining are not included */

err_log( pathinfo( __FILE__, PATHINFO_BASENAME ) . ' included' );
