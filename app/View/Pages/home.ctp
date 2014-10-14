<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

App::uses('Debugger', 'Utility');
?>
<div class="page-header">
    <h2><?php echo __d('cake_dev', 'You’ve successfully installed CakePHP %s.', Configure::version()); ?></h2>
</div>
<?php
if (Configure::read('debug') > 0):
    Debugger::checkSecurityKeys();
endif;
?>
<?php
if (file_exists(WWW_ROOT . 'css' . DS . 'cake.generic.css')):
?>
<p id="url-rewriting-warning" style="background-color:#e32; color:#fff;">
    <?php echo __d('cake_dev', 'URL rewriting is not properly configured on your server.'); ?>
    1) <a target="_blank" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html" style="color:#fff;">Help me configure it</a>
    2) <a target="_blank" href="http://book.cakephp.org/2.0/en/development/configuration.html#cakephp-core-configuration" style="color:#fff;">I don't / can't use URL rewriting</a>
</p>
<?php
endif;
?>
<p>
<?php
    if (version_compare(PHP_VERSION, '5.2.8', '>=')):
        echo '<div class="alert alert-success">';
            echo __d('cake_dev', 'Your version of PHP is 5.2.8 or higher.');
        echo '</div>';
    else:
        echo '<div class="alert alert-danger">';
            echo __d('cake_dev', 'Your version of PHP is too low. You need PHP 5.2.8 or higher to use CakePHP.');
        echo '</div>';
    endif;
?>
</p>
<p>
    <?php
        if (is_writable(TMP)):
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'Your tmp directory is writable.');
            echo '</div>';
        else:
            echo '<div class="alert alert-danger">';
                echo __d('cake_dev', 'Your tmp directory is NOT writable.');
            echo '</div>';
        endif;
    ?>
</p>
<p>
    <?php
        $settings = Cache::settings();
        if (!empty($settings)):
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'The %s is being used for core caching. To change the config edit %s', '<em>'. $settings['engine'] . 'Engine</em>', 'APP/Config/core.php');
            echo '</div>';
        else:
            echo '<div class="alert alert-danger">';
                echo __d('cake_dev', 'Your cache is NOT working. Please check the settings in %s', 'APP/Config/core.php');
            echo '</div>';
        endif;
    ?>
</p>
<p>
    <?php
        $filePresent = null;
        if (file_exists(APP . 'Config' . DS . 'database.php')):
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'Your database configuration file is present.');
                $filePresent = true;
            echo '</div>';
        else:
            echo '<div class="alert alert-danger">';
                echo __d('cake_dev', 'Your database configuration file is NOT present.');
                echo '<br/>';
                echo __d('cake_dev', 'Rename %s to %s', 'APP/Config/database.php.default', 'APP/Config/database.php');
            echo '</div>';
        endif;
    ?>
</p>
<?php
if (isset($filePresent)):
    App::uses('ConnectionManager', 'Model');
    try {
        $connected = ConnectionManager::getDataSource('default');
    } catch (Exception $connectionError) {
        $connected = false;
        $errorMsg = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')):
            $attributes = $connectionError->getAttributes();
            if (isset($errorMsg['message'])):
                $errorMsg .= '<br />' . $attributes['message'];
            endif;
        endif;
    }
?>
<p>
    <?php
        if ($connected && $connected->isConnected()):
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'CakePHP is able to connect to the database.');
            echo '</div>';
        else:
            echo '<div class="alert alert-danger">';
                echo __d('cake_dev', 'CakePHP is NOT able to connect to the database.');
                echo '<br /><br />';
                echo $errorMsg;
            echo '</div>';
        endif;
    ?>
</p>
<?php endif; ?>
<?php
    App::uses('Validation', 'Utility');
    if (!Validation::alphaNumeric('cakephp')):
        echo '<div class="alert alert-danger">';
            echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
            echo '<br/>';
            echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
        echo '</div>';
    endif;
?>

<p>
    <?php
        if (CakePlugin::loaded('DebugKit')):
            echo '<div class="alert alert-success">';
                echo __d('cake_dev', 'DebugKit plugin is present');
            echo '</div>';
        else:
            echo '<div class="alert alert-danger">';
                echo __d('cake_dev', 'DebugKit is not installed. It will help you inspect and debug different aspects of your application.');
                echo '<br/>';
                echo __d('cake_dev', 'You can install it from %s', $this->Html->link('GitHub', 'https://github.com/cakephp/debug_kit'));
            echo '</div>';
        endif;
    ?>
</p>

<div class="page-header">
    <h3><?php echo __d('cake_dev', 'Editing this Page'); ?></h3>
</div>
<p>
<?php
echo __d('cake_dev', 'To change the content of this page, edit: %s.<br />
To change its layout, edit: %s.<br />
You can also add some CSS styles for your pages at: %s.',
    'APP/View/Pages/home.ctp', 'APP/View/Layouts/default.ctp', 'APP/webroot/css');
?>
</p>
<br/>
<br/>
