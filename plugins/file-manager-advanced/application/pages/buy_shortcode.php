<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap fma" style="background:#fff; padding: 20px; border:1px solid #ccc;">
<h3><?php _e('Shortcodes','file-manager-advanced')?> <a href="https://advancedfilemanager.com/documentation/" class="button" target="_blank"><?php _e('Documentation','file-manager-advanced')?></a></h3> 
<?php if(class_exists('file_manager_advanced_shortcode')) { ?>
<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
<p><strong><?php _e('Congratulations,','file-manager-advanced')?> </strong><?php _e('You have Installed Advanced File Manager Shortcode Successfully. Start working with shortcode.','file-manager-advanced')?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
<?php } else { ?>
<div id="setting-error-settings_updated" class="error settings-error notice">
<p style="color:red"><strong><?php _e('This is Pro Feature of Advanced File Manager, Please Buy <a href="https://advancedfilemanager.com/pricing" target="_blank">Advanced File Manager Shortcode</a> Addon to make shortcode work for frontend. <a href="https://advancedfilemanager.com/pricing" target="_blank" class="button button-primary">Buy Now</a>','file-manager-advanced')?></strong></p>
</div>
<?php } ?>
<h3>Shortcode - Logged In Users: <a href="https://advancedfilemanager.com/shortcode-demo/" target="_blank" class="">Click here for demo</a></h3>
<p><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" hide="plugins" operations="upload,download" block_users="5" view="grid" theme="light" lang ="en" upload_allow="all"]</code></p>

<h3>Shortcode - Non Logged In Users:</h3>
<p><code>[file_manager_advanced login="no" path="wp-content" hide="plugins" operations="upload,download" view="grid" theme="light" lang ="en" upload_allow="all"]</code></p>

<h3>Advance Shortcode - User Role Conditions:</h3>
<p><code>[fma_user_role role="subscriber,editor"]<br/>
[file_manager_advanced login="yes" roles="subscriber,editor" path="wp-content" hide="plugins" operations="upload"  view="list" theme="light" lang ="en"]<br/>
[/fma_user_role]<br/>
[fma_user_role role="administrator"]<br/>
[file_manager_advanced login="yes" roles="administrator" path="wp-content/plugins" operations="upload" view="list" theme="light" lang ="en"]<br/>[/fma_user_role]</code><br/> <strong>And so on many more condtions.</strong></p>

<h3>Advance Shortcode - User Conditions:</h3>
<p><code>[fma_user user="1,2"]<br/>
[file_manager_advanced login="yes" roles="subscriber,editor" path="wp-content" hide="plugins" operations="upload"  view="list" theme="light" lang ="en"]<br/>
[/fma_user]<br/>
[fma_user user="3"]<br/>
[file_manager_advanced login="yes" roles="administrator" path="wp-content/plugins" operations="upload" view="list" theme="light" lang ="en"]<br/>[/fma_user]</code><br/> <strong>And so on many more condtions.</strong> <p style="color:red">Note: user="1,2" here 1,2 are user ids.</p></p>

<h3>Parameters: </h3>
<table class="form-table" border="1" style="text-align:center">
<tr>
<td><strong>Parameter Name</strong></td>
<td><strong>Value</strong></td>
<td><strong>Description</strong></td>
<td><strong>Usage</strong></td>
</tr>
<tr>
<td>login</td>
<td>yes/no</td>
<td>yes -> Allow logged in users, no -> Non logged in users</td>
<td><code>[file_manager_advanced login="yes"]</code> - logged in users<br><br>
<code>[file_manager_advanced login="no"]</code> - non logged in  users or visitors<br><br>
<strong>You can use given parameters for both shortcodes.</strong></td>
</tr>

<tr>
<td>roles</td>
<td>all / administrator, author</td>
<td>all -> Allow all user roles , use: roles="all"</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator"]</code></td>
</tr>
<tr>
<td>path</td>
<td><p>(1) wp-content/uploads</p>
<p>(2) <strong>%</strong> - Root Directory</p>
<p>(3) <strong>$</strong> - Will generate logged in users personal folder of their username (unique) under location <strong>"wp-content/uploads/file-manager-advanced/users"</strong>, user path="$" in shortcode.</p>
<p>(4) <strong>wp-content/uploads/file-manager-advanced/users</strong> - you can check all users personal folders under this path.</p>
</td>
<td>Any Folder Path, access selected folder path</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content/uploads"]</code></td>
</tr>
<tr>
<td>path_type</td>
<td>inside/outside</td>
<td>use "outside", if you are using directory outside wordpress root directory, default: inside</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content/uploads" path_type="inside"]</code><strong>Use "url" parameter with outside as url = "https://anyoutsidewebsite.com"</strong></td>
</tr>
<tr>
<td>hide</td>
<td>plugins</td>
<td>will hide plugins folder</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins"]</code></td>
</tr>
<tr>
<td>operations</td>
<td>all / mkdir, mkfile, rename, duplicate, paste, ban, archive, extract, copy, cut, edit, rm, download, upload, resize, search, info, help, empty</td>
<td>all -> allow all operations, you can select according to your use </td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download"]</code></td>
</tr>
<tr>
<td>block_users</td>
<td>1,5</td>
<td>User ids, you want to block, use this when you want to block any user from access of file manager. </td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5"]</code></td>
</tr>
<tr>
<td>view</td>
<td>list / grid</td>
<td>Files and Folder view</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid"]</code></td>
</tr>
<tr>
<td>theme</td>
<td>light / dark / grey / windows10 / bootstrap</td>
<td>File Manager Theme</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light"]</code></td>
</tr>
<tr>
<td>lang</td>
<td>en </td>
<td>Copy Language Code Given Below</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" lang ="en"]</code></td>
</tr>
<tr>
<td>dateformat</td>
<td>M d, Y h:i A</td>
<td>File manager files date format</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A"]</code></td>
</tr>
<tr>
<td>hide_path</td>
<td>yes/no</td>
<td>Will hide actual file path on preview. Default: no</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A" hide_path="no"]</code></td>
</tr>
<tr>
<td>enable_trash</td>
<td>yes/no</td>
<td>Will display trash in file manager on front shortcode page. Default: no</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A" hide_path="no" enable_trash="no"]</code></td>
</tr>
<tr>
<td>height</td>
<td>500</td>
<td>Will adjust in file manager height on front shortcode page. Default: blank (auto)</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A" hide_path="no" enable_trash="no" height=""]</code></td>
</tr>
<tr>
<td>width</td>
<td>800</td>
<td>Will adjust in file manager width on front shortcode page. Default: blank (auto)</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A" hide_path="no" enable_trash="no" height="" width=""]</code></td>
</tr>
<tr>
<td>ui</td>
<td>1) files -> Will Display only files (no toolbar, no left side bar) (use ui="files" in shortcode, below parameters will not work with "files" parameter)<br>
    2) toolbar,tree,path,stat -> Use: ui="toolbar,tree,path,stat" , you can remove any with your choice</td>
<td>Will display only selected ui. Default: blank (all)</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A" hide_path="no" enable_trash="no" height="" width="" ui="toolbar,tree,path,stat"]</code></td>
</tr>
<tr>
<td>allowed_upload</td>
<td>1) upload_allow="all" -> allow all files to upload.<br>
    2) Specific mime types like: upload_allow= "image/vnd.adobe.photoshop,image/png" </td>
<td>Will allow file mimes type to upload.. Default: all</td>
<td><code>[file_manager_advanced login="yes" roles="author,editor,administrator" path="wp-content" path_type="inside" hide="plugins" operations="mkdir,download" block_users="1,5" view="grid" theme="light" dateformat="M d, Y h:i A" hide_path="no" enable_trash="no" height="" width="" ui="toolbar,tree,path,stat" upload_allow= "image/vnd.adobe.photoshop,image/png"]</code></td>
</tr>
</table>
<h3>List of Operations</h3>
<ul>
<li><span class="num">1.</span> <span><strong>mkdir -</strong></span> <span>Create new directory or folder</span> </li>
<li><span class="num">2.</span> <span><strong>mkfile -</strong> <span>Create new file</span> </li>
<li><span class="num">3.</span> <span><strong>rename -</strong></span> <span>Rename a file or folder</span> </li>
<li><span class="num">4.</span> <span><strong>duplicate -</strong></span> <span>Duplicate or clone a folder or file</span> </li>
<li><span class="num">5.</span> <span><strong>paste -</strong></span> <span> Paste a file or folder</span> </li>
<li><span class="num">6.</span> <span><strong>help -</strong></span> <span>Help desk</span> </li>
<li><span class="num">7.</span> <span><strong>archive -</strong></span> <span>Create a archive or zip</span> </li>
<li><span class="num">8.</span> <span><strong>extract -</strong></span> <span>Extract archive or zipped file</span> </li>
<li><span class="num">9.</span> <span><strong>copy -</strong></span> <span>Copy files or folders</span> </li>
<li><span class="num">10.</span> <span><strong>cut -</strong></span> <span>Simple cut a file or folder</span> </li>
<li><span class="num">11.</span> <span><strong>edit -</strong></span> <span>Edit a file or folder</span> </li>
<li><span class="num">12.</span> <span><strong>rm -</strong></span> <span>Remove or delete files and folders</span> </li>
<li><span class="num">13.</span> <span><strong>download -</strong></span> <span>Download files and folders</span> </li>
<li><span class="num">14.</span> <span><strong>upload -</strong></span> <span>Upload files</span> </li>
<li><span class="num">15.</span> <span><strong>search -</strong> </span> <span>Search things</span> </li>
<li><span class="num">16.</span> <span><strong>info -</strong></span> <span>Info of file or folder</span> </li>
</ul>
<h3>List Of Languages</h3>
<?php $locales =  array('English'=>'en',
                          'Arabic'=>'ar',
                          'Bulgarian' => 'bg',
                          'Catalan' => 'ca',
                          'Czech' => 'cs',
                          'Danish' => 'da',
                          'German' => 'de',
                          'Greek' => 'el',
                          'Espanol' => 'es',
                          'Persian-Farsi' => 'fa',
                          'Faroese translation' => 'fo',
                          'French' => 'fr',
                          'Hebrew' => 'he',
                          'hr' => 'hr',
                          'magyar' => 'hu',
                          'Indonesian' => 'id',
                          'Italiano' => 'it',
                          'Japanese' => 'jp',
                          'Korean' => 'ko',
                          'Dutch' => 'nl',
                          'Norwegian' => 'no',
                          'Polski' => 'pl',
                          'Portugues' => 'pt_BR',
                          'Romana' => 'ro',
                          'Russian' => 'ru',
                          'Slovak' => 'sk',
                          'Slovenian' => 'sl',
                          'Serbian' => 'sr',
                          'Swedish' => 'sv',
                          'Turkce' => 'tr',
                          'Uyghur' => 'ug_CN',
                          'Ukrainian' => 'uk',
                          'Vietnamese' => 'vi',
                          'Simplified Chinese' => 'zh_CN',
                          'Traditional Chinese' => 'zh_TW',
                          );?>
						  <table>
						  <tr>
						  <th>Language</th>
						  <th>Code</th>
						  </tr>
						  <?php foreach($locales as $lang => $code) {?>
						  <tr>
						  <td><?php echo esc_attr($lang);?></td>
						  <td><code><?php  echo esc_attr($code);?></code></td>
						  </tr>
						  <?php } ?>
						  </table>
</div>