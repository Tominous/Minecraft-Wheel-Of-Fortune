<div class="container">
    <div class="row">
        <div class="col">
            <h1>Wheel of fortune connection settings</h1>
        </div>
    </div>
    <div class="row">
        <form id="form-items" action="" method="post" name="admin-wof-setting">
            <div id="form-base-fields">
                <div class="wof-form-group">
                    <h4>Minecraft query settings</h4>
                    <div class="base-fields form-row">
                        <div class="col">
                            <input type="text" class="form-control <?php echo $classes['host']; ?>" placeholder="MC server IP" name="host" value="<?php echo $this->config['host']; ?>"/>
                            <small class="form-text text-muted">IP of Minecraft server</small>
                            <div class="invalid-feedback">Field has to be not empty</div>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control <?php echo $classes['port']; ?>" placeholder="MC server port" name="port" value="<?php echo $this->config['port']; ?>" />
                            <small class="form-text text-muted">Server port</small>
                            <div class="invalid-feedback">Field has to be not empty</div>
                        </div>
                        <div class="col">
                            <input type="password" class="form-control <?php echo $classes['password']; ?>" placeholder="MC query password" name="password" value="<?php echo $this->config['password']; ?>" />
                            <small class="form-text text-muted">MC query password</small>
                            <div class="invalid-feedback">Field has to be not empty</div>
                        </div>
                    </div>
                </div>
                <h4>Database connection settings</h4>
                <div class="base-fields form-row">
                    <div class="col">
                        <input type="text" class="form-control <?php echo $classes['db_ip']; ?>" placeholder="Database ip" name="db_ip" value="<?php echo $this->config['db_ip']; ?>" />
                        <small class="form-text text-muted">IP server of database to connect</small>
                        <div class="invalid-feedback">Field has to be not empty</div>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control <?php echo $classes['db_port']; ?>" placeholder="Database port" name="db_port" value="<?php echo $this->config['db_port']; ?>" />
                        <small class="form-text text-muted">Port of database to connect</small>
                        <div class="invalid-feedback">Field has to be not empty</div>
                    </div>
                </div>
                <div class="base-fields form-row">
                    <div class="col">
                        <input type="text" class="form-control <?php echo $classes['db_name']; ?>" placeholder="Database name" name="db_name" value="<?php echo $this->config['db_name']; ?>" />
                        <small class="form-text text-muted">Name of database to connect</small>
                        <div class="invalid-feedback">Field has to be not empty</div>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control <?php echo $classes['db_user']; ?>" placeholder="Database user" name="db_user" value="<?php echo $this->config['db_user']; ?>"/>
                        <small class="form-text text-muted">Database account what will be used to connect</small>
                        <div class="invalid-feedback">Field has to be not empty</div>
                    </div>
                    <div class="col">
                        <input type="password" class="form-control <?php echo $classes['db_pass']; ?>" placeholder="Database user" name="db_pass" value="<?php echo $this->config['db_pass']; ?>" />
                        <small class="form-text text-muted">Password to database</small>
                        <div class="invalid-feedback">Field has to be not empty</div>
                    </div>
                </div>
            </div>
            <br />
            <input type="submit" id="wof-items-submit" type="submit" class="btn btn-primary" value="Save">
        </form>
    </div>
    <div class="row">
        <div class="alert alert-warning" role="alert">
            <span style="font-size: 1.5em; vertical-align: top;" class="typcn typcn-info-outline"></span><span> If Minecraft server is on same machine as website write <b>127.0.0.1</b> as server IP</span>
        </div>
    </div>
</div>

<style></style>