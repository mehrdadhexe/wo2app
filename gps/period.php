<?php
/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/27/2018
 * Time: 4:58 PM
 */


if(isset($_POST["submit_dore"]) ) {
    $array = array();
    $_POST['shanbe']['enable'] = ($_POST['shanbe_chk'] == 'on') ? 1 : 0;
    $_POST['1shanbe']['enable'] = ($_POST['1shanbe_chk'] == 'on') ? 1 : 0;
    $_POST['2shanbe']['enable'] = ($_POST['2shanbe_chk'] == 'on') ? 1 : 0;
    $_POST['3shanbe']['enable'] = ($_POST['3shanbe_chk'] == 'on') ? 1 : 0;
    $_POST['4shanbe']['enable'] = ($_POST['4shanbe_chk'] == 'on') ? 1 : 0;
    $_POST['5shanbe']['enable'] = ($_POST['5shanbe_chk'] == 'on') ? 1 : 0;
    $_POST['6shanbe']['enable'] = ($_POST['6shanbe_chk'] == 'on') ? 1 : 0;
    $array[] = array('enable'=> $_POST['shanbe']['enable'] , 'start'=>(int)$_POST['shanbe']['start'],'end'=>(int)$_POST['shanbe']['end'],'time_space'=> (int)$_POST['shanbe']['time_space']);
    $array[] = array('enable'=> $_POST['1shanbe']['enable'] , 'start'=>(int)$_POST['1shanbe']['start'],'end'=>(int)$_POST['1shanbe']['end'],'time_space'=> (int)$_POST['1shanbe']['time_space']);
    $array[] = array('enable'=> $_POST['2shanbe']['enable'] , 'start'=>(int)$_POST['2shanbe']['start'],'end'=>(int)$_POST['2shanbe']['end'],'time_space'=> (int)$_POST['2shanbe']['time_space']);
    $array[] = array('enable'=> $_POST['3shanbe']['enable'] , 'start'=>(int)$_POST['3shanbe']['start'],'end'=>(int)$_POST['3shanbe']['end'],'time_space'=> (int)$_POST['3shanbe']['time_space']);
    $array[] = array('enable'=> $_POST['4shanbe']['enable'] , 'start'=>(int)$_POST['4shanbe']['start'],'end'=>(int)$_POST['4shanbe']['end'],'time_space'=> (int)$_POST['4shanbe']['time_space']);
    $array[] = array('enable'=> $_POST['5shanbe']['enable'] , 'start'=>(int)$_POST['5shanbe']['start'],'end'=>(int)$_POST['5shanbe']['end'],'time_space'=> (int)$_POST['5shanbe']['time_space']);
    $array[] = array('enable'=> $_POST['6shanbe']['enable'] , 'start'=>(int)$_POST['6shanbe']['start'],'end'=>(int)$_POST['6shanbe']['end'],'time_space'=> (int)$_POST['6shanbe']['time_space']);
    update_option('mr2app_period',$array);
}

$period = get_option('mr2app_period');

?>


<div class="wrap">
    <h1>
        بازه زمانی
    </h1>
    <form action="" method="post">
        <table class="form-table " style="direction: rtl">
            <tbody>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > شنبه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"  <?= $period[0]['enable'] == 1 ? 'checked' : '' ;?>  name="shanbe_chk" />
                    <input type="number" value="<?= $period[0]['start']?>"  name="shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[0]['end']?>"   name="shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[0]['time_space']?>"  name="shanbe[time_space]"  placeholder=" آپدیت در دقیقه" >
                </td>
            </tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > 1 شنبه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"   <?= $period[1]['enable'] == 1 ? 'checked' : '' ;?> name="1shanbe_chk" />
                    <input type="number" value="<?= $period[1]['start']?>"  name="1shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[1]['end']?>"   name="1shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[1]['time_space']?>"  name="1shanbe[time_space]"  placeholder=" آپدیت در دقیقه" >
                </td>
            </tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > 2 شنبه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"   <?= $period[2]['enable'] == 1 ? 'checked' : '' ;?> name="2shanbe_chk" />
                    <input type="number" value="<?= $period[2]['start']?>"  name="2shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[2]['end']?>"   name="2shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[2]['time_space']?>"  name="2shanbe[time_space]"  placeholder="آپدیت در دقیقه" >
                </td>
            </tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > 3 شنبه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"    <?= $period[3]['enable'] == 1 ? 'checked' : '' ;?> name="3shanbe_chk" />
                    <input type="number" value="<?= $period[3]['start']?>"  name="3shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[3]['end']?>"   name="3shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[3]['time_space']?>"  name="3shanbe[time_space]"  placeholder="آپدیت در دقیقه" >
                </td>
            </tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > 4 شنبه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"    <?= $period[4]['enable'] == 1 ? 'checked' : '' ;?> name="4shanbe_chk" />
                    <input type="number" value="<?= $period[4]['start']?>"  name="4shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[4]['end']?>"   name="4shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[4]['time_space']?>"  name="4shanbe[time_space]"  placeholder="آپدیت در دقیقه" >
                </td>
            </tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > 5 شنبه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"    <?= $period[5]['enable'] == 1 ? 'checked' : '' ;?> name="5shanbe_chk" />
                    <input type="number" value="<?= $period[5]['start']?>"  name="5shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[5]['end']?>"   name="5shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[5]['time_space']?>"  name="5shanbe[time_space]"  placeholder="آپدیت در دقیقه" >
                </td>
            </tr>
            <tr valign="top" class="">
                <th scope="row" class="titledesc">
                    <label > جمعه </label>
                </th>
                <td class="forminp">
                    فعال / غیر فعال <input type="checkbox"    <?= $period[6]['enable'] == 1 ? 'checked' : '' ;?> name="6shanbe_chk" />
                    <input type="number" value="<?= $period[6]['start']?>"  name="6shanbe[start]" placeholder=" ساعت شروع" >
                    <input type="number" value="<?= $period[6]['end']?>"   name="6shanbe[end]"    placeholder="ساعت پایان" >
                    <input type="number" value="<?= $period[6]['time_space']?>"  name="6shanbe[time_space]"  placeholder="آپدیت در دقیقه" >
                </td>
            </tr>
            <tr>
                <th>
                    <input type="submit" name="submit_dore" class="button button-primary" value="ذخیره" />
                </th>
            </tr>
            </tbody>
        </table>

    </form>
</div>