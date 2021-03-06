<div id="setLangDiv" class="lightbox popout">
    <br><br>
    <h3 class="settingsh3"><?php echo $langpanel1; ?></h3>
    <p class="uploadP" onclick="selectLang('en');"><img src="img/cd-icon-english.png" class="headerIcon"> English</p>
    <p class="uploadP" onclick="selectLang('pl');"><img src="img/cd-icon-polish.png" class="headerIcon"> Polish</p>
    <p class="uploadP" onclick="selectLang('fr');"><img src="img/cd-icon-french.png" class="headerIcon"> Français</p>
    <br>
    <?php if(!isset($_COOKIE['sy_lang'])) { ?>
        <h3 class="settingsh3" style="font-size:12px;font-weight:lighter;">The standard language is <span style="font-weight:bolder;">English</span>. You can change it now or later in the settings panel.</h3>
    <?php } else { ?>
        <h3 class="settingsh3" style="font-size:12px;font-weight:lighter;"><?php echo $langpanel2; ?> <span style="font-weight:bolder;"><?php echo $lang_name; ?></span></h3>
    <?php } ?>
    <br>
    <?php if(!isset($_COOKIE['sy_lang'])) { ?>
        <h3 class="settingsh3" style="font-size:12px;font-weight:bolder;color:#4183D7;" onclick="selectLang('<?=$load_lang_code?>');">Skip</h3>
    <?php } else { ?>
        <h3 class="settingsh3" style="font-size:12px;font-weight:bolder;color:#4183D7;" onclick="$('#setLangDiv').hide(); $('#background4').slideUp(250, 'swing');"><?php echo $langpanel3; ?></h3>
    <?php } ?>
    <br><br>
</div>

<?php
if(!isset($_COOKIE['sy_lang'])) { ?>
    <script>
        $("#setLangDiv").show();
        $("#background4").slideDown(250, "swing");
    </script>
<?php }