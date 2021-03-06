<section>
    <div class="supsystic-item supsystic-panel">
        <div id="bupBackupWrapper">
            <div id="bupAdminStorageTable" style="width: 100%;">
                <?php
                if(!empty($backups)):
                    foreach ($backups as $id => $type):
                    $backupType = key($type);
                    $backupStartDateTime  = (!empty($logs[$id]['content'])) ? $model->getBackupStartTimeFromLog($logs[$id]['content']) : '' ;
                    $backupFinishDateTime = (!empty($logs[$id]['content'])) ?$model->getBackupFinishTimeFromLog($logs[$id]['content']) : '';
                    if($backupType == 'ftp'):
                        $backup = $type['ftp'];
                    ?>
                    <!--  FTP files rendering start    -->
                    <div class="backupBlock">
                        <p>
                            Backup to <b>FTP</b> / ID <b><?php echo $id; ?></b><?php echo !empty($backupStartDateTime) ?' / Start: <b>' . $backupStartDateTime . '</b>' : ''?> / Finish: <b><?php echo (isset($backup['zip']) ? $backup['zip']['date'].' '.$backup['zip']['time'] : $backup['sql']['date'].' '.$backup['sql']['time'])?></b>
                        </p>
                        <div align="left" id="MSG_EL_ID_<?php echo $id; ?>"></div>

                        <div id="bupControl-<?php echo $id?>">
                            <!-- Hides "Send to" button if the PRO version isn't activated -->
                            <?php if (null !== frameBup::_()->getModule('license')): ?>
                            <div>
                                <a href="#" onclick="return false;" class="bupSendTo" style="font-size:.9em">Send to &rarr;</a>
                            </div>
                            <?php endif; ?>

                            <!-- Backup sendTo providers  loop start -->
                            <div class="bupSendToProviders" style="display: none; font-size:.9em;">
                                <?php foreach ($providers as $provider): ?>
                                <a
                                    href="#"
                                    id="<?php echo $id; ?>"
                                    class="bupSendToBtn"
                                    onclick="return false;"
                                    data-provider="<?php echo $provider['provider']; ?>"
                                    data-action="<?php echo $provider['action']; ?>"
                                    title="Send backup to <?php echo $provider['label']; ?>"
                                    <?php foreach ($backup as $data): $backupfiles[] = $data['name']; endforeach;?>
                                    data-files="<?php echo implode(',', $backupfiles); ?><?php unset($backupfiles); ?>"
                                ><?php echo $provider['label']; ?></a>&nbsp;
                                <?php endforeach; ?>
                            </div>
                            <!-- Backup sendTo providers  loop end -->

                            <table>
                                <tbody>
                                    <?php foreach ($backup as $type => $data): ?>

                                    <tr class="tabStr" id="<?php echo $data['name']; ?>">
                                        <td>
                                            <?php echo ($type == 'zip' ? 'Filesystem' : 'Database'); ?>
                                        </td>
                                        <td>
                                            <!-- restoreButton -->
                                            <button class="button button-primary button-small bupRestore" data-id="<?php echo $id; ?>" data-filename="<?php echo $data['name']; ?>" >
                                                <?php langBup::_e('Restore'); ?>
                                            </button>
                                            <!-- /restoreButton -->

                                            <!-- downloadButton -->
                                            <button class="button button-primary button-small bupDownload" data-filename="<?php echo $data['name']; ?>">
                                                <?php langBup::_e('Download'); ?>
                                            </button>
                                            <!-- /downloadButton -->

                                            <!-- deleteButton -->
                                            <button class="button button-primary button-small bupDelete" data-id="<?php echo $id; ?>" data-filename="<?php echo $data['name']; ?>">
                                                <?php langBup::_e('Delete'); ?>
                                            </button>
                                            <!-- /deleteButton -->
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if(!empty($logs[$id]['content'])):?>
                                <span class="bupShowLogDlg" data-log="<?php echo nl2br($logs[$id]['content'])?>">Show Backup Log</span>
                            <?php else: ?>
                                <b>Log is clear.</b>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr/>
                    <!--  FTP files rendering end    -->


                    <!--  GoogleDrive files rendering start    -->
                    <?php
                    elseif($backupType == 'gdrive'):
                        $files = $type['gdrive'];
                        ?>
                        <div class="backupBlock">
                            <p>
                                Backup to <b>GoogleDrive</b> / ID <b><?php echo $id; ?></b><?php echo !empty($backupStartDateTime) ?' / Start: <b>' . $backupStartDateTime . '</b>' : ''?><?php echo !empty($backupFinishDateTime) ?' / Finish: <b>' . $backupFinishDateTime . '</b>' : ''?>
                            </p>
                            <div id="bupGDriveAlerts-<?php echo $id; ?>"></div>
                            <div id="bupControl-<?php echo $id?>">
                                <?php if(isset($files['sql']['labels']['trashed']) && ($files['sql']['labels']['trashed'] === false) || isset($files['zip']['labels']['trashed']) && ($files['zip']['labels']['trashed'] === false)): ?>
                                    <table>
                                    <tbody>
                                    <?php foreach($files as $type=>$file): ?>
                                        <tr id="<?php echo $type.'-'.$id; ?>">
                                            <td>
                                                <?php echo ($type == 'zip')? 'Filesystem' : 'Database'?>
                                            </td>
                                            <td>
                                                <img src="<?php echo $file['iconLink']; ?>" /> <?php echo $file['title']; ?>
                                            </td>
                                            <td>
                                                <button data-row-id="<?php echo $id; ?>"
                                                        data-file-url="<?php echo $file['downloadUrl']; ?>"
                                                        data-file-name="<?php echo $file['title']; ?>"
                                                        data-file-type="<?php echo $type; ?>"
                                                        class="button button-primary button-small bupGDriveRestore"
                                                    >
                                                    <?php langBup::_e('Restore'); ?>
                                                </button>
                                                <button data-row-id="<?php echo $id; ?>"
                                                        data-file-id="<?php echo $file['id']; ?>"
                                                        data-filename="<?php echo $file['title']; ?>"
                                                        data-file-type="<?php echo $type; ?>"
                                                        class="button button-primary button-small bupGDriveDelete"
                                                    >
                                                    <?php langBup::_e('Delete'); ?>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                    </table>
                                <?php endif; ?>
                                <?php if(!empty($logs[$id]['content'])):?>
                                    <span class="bupShowLogDlg" data-log="<?php echo nl2br($logs[$id]['content'])?>">Show Backup Log</span>
                                <?php else: ?>
                                    <b>Log is clear.</b>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr/>
                    <!--  GoogleDrive files rendering end    -->

                    <!--  OneDrive files rendering start    -->
                    <?php
                    elseif($backupType == 'onedrive'):
                        $files = $type['onedrive'];
                        ?>
                        <div class="backupBlock">
                            <p>
                                Backup to <b>OneDrive</b> / ID <b><?php echo $id; ?></b><?php echo !empty($backupStartDateTime) ?' / Start: <b>' . $backupStartDateTime . '</b>' : ''?><?php echo !empty($backupFinishDateTime) ?' / Finish: <b>' . $backupFinishDateTime . '</b>' : ''?>
                            </p>
                            <div id="bupOnedriveAlerts-<?php echo $id; ?>"></div>
                            <div id="bupControl-<?php echo $id?>">
                                <table>
                                    <tbody>
                                        <?php foreach($files as $type=>$file):?>
                                            <?php if ($file->type === 'file'): ?>
                                                <tr id="backup-<?php echo $file->id; ?>" data-id="<?php echo $id; ?>" data-filename="<?php echo $file->name; ?>">
                                                    <td>
                                                        <?php echo ($type == 'zip')? 'Filesystem' : 'Database'?>
                                                    </td>
                                                    <td>
                                                        <?php echo $file->name; ?>
                                                    </td>
                                                    <td>
                                                        <button data-row-id="<?php echo $id; ?>"
                                                                data-file-id="<?php echo $file->id; ?>"
                                                                data-file-name="<?php echo $file->name; ?>"
                                                                class="button button-primary button-small bupRestoreOnedrive"
                                                            >
                                                            <?php langBup::_e('Restore'); ?>
                                                        </button>
                                                        <button
                                                            data-file-id="<?php echo $file->id; ?>"
                                                            class="button button-primary button-small onedriveDelete"
                                                            >
                                                            <?php langBup::_e('Delete'); ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <?php if(!empty($logs[$id]['content'])):?>
                                    <span class="bupShowLogDlg" data-log="<?php echo nl2br($logs[$id]['content'])?>">Show Backup Log</span>
                                <?php else: ?>
                                    <b>Log is clear.</b>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr/>
                <!--  OneDrive files rendering end    -->

                <!--  Amazon files rendering start    -->
                    <?php
                    elseif($backupType == 'amazon'):
                        $files = $type['amazon'];
                        ?>
                        <div class="backupBlock">
                            <p>
                                Backup to <b>Amazon S3</b> / ID <b><?php echo $id; ?></b><?php echo !empty($backupStartDateTime) ?' / Start: <b>' . $backupStartDateTime . '</b>' : ''?><?php echo !empty($backupFinishDateTime) ?' / Finish: <b>' . $backupFinishDateTime . '</b>' : ''?>
                            </p>
                            <div id="bupAmazonAlerts-<?php echo $id;?>"></div>
                            <div id="bupControl-<?php echo $id?>">
                                <table>
                                    <tbody>
                                        <?php foreach($files as $type => $file):?>
                                            <tr id="<?php echo $id;?>">
                                                <td>
                                                    <?php echo ($type == 'zip')? 'Filesystem' : 'Database'?>
                                                </td>
                                                <td><?php echo $file['file']; ?></td>
                                                <td>
                                                    <button class="button button-primary button-small bupAmazonS3Restore" data-row-id="<?php echo $id; ?>" data-filename="<?php echo $file['file']; ?>">
                                                        <?php langBup::_e('Restore'); ?>
                                                    </button>
                                                    <button class="button button-primary button-small bupAmazonS3Delete" data-row-id="<?php echo $id; ?>" data-filename="<?php echo $file['file']; ?>">
                                                        <?php langBup::_e('Delete'); ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <?php if(!empty($logs[$id]['content'])):?>
                                    <span class="bupShowLogDlg" data-log="<?php echo nl2br($logs[$id]['content'])?>">Show Backup Log</span>
                                <?php else: ?>
                                    <b>Log is clear.</b>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr/>
                <!--  Amazon files rendering end    -->

                <!--  DropBox files rendering start    -->
                    <?php
                    elseif($backupType == 'dropbox'):
                        $files = $type['dropbox'];
                        ?>
                        <div class="backupBlock">
                            <p>
                                Backup to <b>DropBox</b> / ID <b><?php echo $id; ?></b><?php echo !empty($backupStartDateTime) ?' / Start: <b>' . $backupStartDateTime . '</b>' : ''?><?php echo !empty($backupFinishDateTime) ?' / Finish: <b>' . $backupFinishDateTime . '</b>' : ''?>
                            </p>
                            <div id="bupDropboxAlerts-<?php echo $id; ?>"></div>
                            <div id="bupControl-<?php echo $id?>">
                                <table>
                                    <tbody>
                                    <?php foreach($files as $type=>$file):?>
                                        <tr id="row-<?php echo $type.'-'.$id; ?>">
                                            <td>
                                                <?php echo ($type == 'sql') ? 'Database' : 'Filesystem'; ?>
                                            </td>
                                            <td>
                                                <?php echo basename($file['path']); ?>
                                            </td>
                                            <td>
                                                <button
                                                    class="button button-primary button-small bupDropboxRestore"
                                                    data-filename="<?php echo basename($file['path']); ?>"
                                                    data-row-id="<?php echo $id; ?>"
                                                    >
                                                    <?php langBup::_e('Restore'); ?>
                                                </button>
                                                <button
                                                    class="button button-primary button-small bupDropboxDelete"
                                                    data-filepath="<?php echo $file['path']; ?>"
                                                    data-row-id="<?php echo $id; ?>"
                                                    data-file-type="<?php echo $type; ?>"
                                                    >
                                                    <?php langBup::_e('Delete'); ?>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <?php if(!empty($logs[$id]['content'])):?>
                                    <span class="bupShowLogDlg" data-log="<?php echo nl2br($logs[$id]['content'])?>">Show Backup Log</span>
                                <?php else: ?>
                                    <b>Log is clear.</b>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr/>
                        <!--  DropBox files rendering end    -->

                    <?php endif; ?>
                <?php endforeach;
                else:?>
                    <h3>Backups don't exist!</h3>
                <?php endif;
                ?>
            </div>
            <!-- Log modal window start  -->
            <div id="bupShowLogDlg" title="Backup Log:">
                <p id="bupLogText"></p>
            </div>
            <!-- Log modal window end  -->
        </div>
    </div>
</section>