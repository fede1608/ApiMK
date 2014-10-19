<html>
<head>    <!-- Le styles -->
    <link href="./inv/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./inv/css/shop.css" type="text/css">
    <link href="./inv/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="./inv/css/site.css" rel="stylesheet">
    <link href="./inv/css/mcpictures.css" rel="stylesheet">
</head>
<body style="background: url('img/bg_640x960_3.png'); background-size: cover;">
<strong></strong>

<div id="container">

<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
if (isset($_GET['user']) && isset($_GET['pass'])) {
    $username = $_GET['user'];
    $pass = $_GET['pass'];
    if ($MyBBI->checkUserPass($username, $pass)) {

        $user = $MySQL->execute("SELECT `id`, `name` FROM inventorysql.`inventorysql_users` WHERE name=\"" . $username . "\"");
        if (!isset($user[0])) {
            $MySQL->execute("INSERT INTO inventorysql.`inventorysql_users`(`name`, `password`) VALUES (\"" . $username . "\",\"\")");
            $user = $MySQL->execute("SELECT `id`, `name` FROM inventorysql.`inventorysql_users` WHERE name=\"" . $username . "\"");
        }
        ?>


        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div class="hero-unit" id="user-summary" style="padding: 20px 10px 20px 10px;">
                        <h1>Inventario</h1>
                    </div>
                    <div id="user-data" class="collapse">
                        <div class="row-fluid">
                            <div class="span8">
                                <h2>Inventario <a href="#" id="reload-inventory"><i class="icon icon-refresh ani">
                                            &nbsp;</i></a></h2>
                                <table id="inventory-table">
                                    <tr>
                                        <td class="slot-103"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-102"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-101"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-100"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-9"></td>
                                        <td class="slot-10"></td>
                                        <td class="slot-11"></td>
                                        <td class="slot-12"></td>
                                        <td class="slot-13"></td>
                                        <td class="slot-14"></td>
                                        <td class="slot-15"></td>
                                        <td class="slot-16"></td>
                                        <td class="slot-17"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-18"></td>
                                        <td class="slot-19"></td>
                                        <td class="slot-20"></td>
                                        <td class="slot-21"></td>
                                        <td class="slot-22"></td>
                                        <td class="slot-23"></td>
                                        <td class="slot-24"></td>
                                        <td class="slot-25"></td>
                                        <td class="slot-26"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-27"></td>
                                        <td class="slot-28"></td>
                                        <td class="slot-29"></td>
                                        <td class="slot-30"></td>
                                        <td class="slot-31"></td>
                                        <td class="slot-32"></td>
                                        <td class="slot-33"></td>
                                        <td class="slot-34"></td>
                                        <td class="slot-35"></td>
                                    </tr>
                                    <tr>
                                        <td class="slot-0"></td>
                                        <td class="slot-1"></td>
                                        <td class="slot-2"></td>
                                        <td class="slot-3"></td>
                                        <td class="slot-4"></td>
                                        <td class="slot-5"></td>
                                        <td class="slot-6"></td>
                                        <td class="slot-7"></td>
                                        <td class="slot-8"></td>
                                    </tr>
                                </table>
                            </div>
                            <!--/span-->
                            <div class="span4 fade" style="display:none">
                                <h2>Item</h2>
                                <table class="table" id="slot-detail">
                                    <tr>
                                        <td class="name inventory" colspan="2">0</td>
                                    </tr>
                                    <tr>
                                        <th>ID</th>
                                        <td class="id">0</td>
                                    </tr>
                                    <tr>
                                        <th>Data</th>
                                        <td class="data">0</td>
                                    </tr>
                                    <tr>
                                        <th>Da&#241o</th>
                                        <td class="damage">0</td>
                                    </tr>
                                    <tr>
                                        <th>Cantidad</th>
                                        <td class="count">0</td>
                                    </tr>
                                </table>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                        <div class="row-fluid">
                            <div class="span8">
                                <h2>Pendientes <a href="#" id="reload-pendings"><i class="icon icon-refresh">&nbsp;</i></a>
                                </h2>

                                <form class="form-inline" id="pending-give" method="get" action="">
                                    <table class="table" id="pendings-table">
                                        <thead>
                                        <tr>
                                            <th class="inventory">Nombre</th>
                                            <!--<th>ID</th>
                                            <th>Data</th>
                                            <th>Da&#241o</th>-->
                                            <th class="inventory">Cantidad</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </form>


                            </div>
                            <!--/span-->
                            <div class="span4 fade">
                                <h2>Detalles</h2>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->
                    </div>
                </div>
                <!--/span-->
            </div>
            <!--/row-->


        </div><!--/.fluid-container-->

              <!-- Le javascript
              ================================================== -->
              <!-- Placed at the end of the document so the pages load faster -->
        <script src="./inv/js/jquery.min.js"></script>
        <script src="./inv/js/bootstrap.min.js"></script>
        <script>
            function romanize (num) {
                if (!+num)
                    return false;
                var digits = String(+num).split(""),
                    key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
                        "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
                        "","I","II","III","IV","V","VI","VII","VIII","IX"],
                    roman = "",
                    i = 3;
                while (i--)
                    roman = (key[+digits.pop() + (i * 10)] || "") + roman;
                return Array(+digits.join("") + 1).join("M") + roman;
            }

            var current_userid;
            var current_username;
            $(function () {

                $.getJSON('./inv/api.php', {'p': 'versions'}, function (data) {
                    console.log(data);
                    $('#versions-display .plugin span').text(data.plugin);
                    $('#versions-display .web span').text(data.web);
                });

                current_userid = <?php echo "'".$user[0]['id']."'"; ?>;
                current_username = <?php echo "'".$user[0]['name']."'"; ?>;
                $('#user-summary h1').text(current_username);
                $('#user-summary p').text("<?php echo date(DATE_RFC822); ?>");
                load_inv();
                load_pend();
                $('#user-data').addClass('in');
                $('#slot-detail').parent().removeClass('in');
                $('#inventory-table').on("click", "td.clickable", function (event) {
                    $('#slot-detail').parent().addClass('in');
                    $('#slot-detail').parent().css("display","initial");
                    $('#slot-detail').empty();
                    $('#slot-detail').append('<tr><td class="name inventory" colspan="2">0</td></tr>');
                    $('#slot-detail .name').html($(this).data('name')).text();
                    console.log($(this).data('enchants'));
                    enchants=$(this).data('enchants');
                    for (var ench in enchants) {
                        if (enchants.hasOwnProperty(ench)) {
                            console.log(enchants[ench]);
                            $('#slot-detail').append('<tr><td class="inventory" colspan="2" style="color: #aaaaaa;">'+enchants[ench].name+' '+romanize(enchants[ench].level)+'</td></tr>');
                        }
                    }
                    metas=$(this).data('metas');
                    for (var meta in metas) {
                        if (metas.hasOwnProperty(meta)) {
                            console.log(metas[meta]);
                            $('#slot-detail').append('<tr><td class="inventory" colspan="2">'+metas[meta]+'</td></tr>');
                        }
                    }
                    $('#slot-detail').append('<tr><th class="inventory" >Cantidad:</th><td class="inventory" >x'+$(this).data('count')+'</td></tr>');

                    //$('#slot-detail .id').text($(this).data('id'));
                    //$('#slot-detail .data').text($(this).data('data'));
                    //$('#slot-detail .damage').text($(this).data('damage'));
                    //$('#slot-detail .count').text($(this).data('count'));
                });
                $('#pendings-table').on("click", "a.details", function (event) {
                    event.preventDefault();
                });

                /** Initial loading of item names **/
                $.getJSON('./inv/api.php', {'p': 'items'}, function (data) {
                    console.log(data);
                    $('.select-items').empty();
                    $.each(data.items, function (index, value) {
                        $('.select-items').append('<option value="' + index + '">' + value + '</option>');
                    });
                });
                $('#pending-give .add-item').change(function (e) {
                    $('#pending-give .add-item-showid').text($(this).val());
                });
                /** Reload functions **/
                $('#reload-inventory').click(function (event) {
                    event.preventDefault();
                    $(this).children('i').addClass('ani rotate');
                    setTimeout(function () {
                        $('#reload-inventory i').removeClass('ani rotate');
                    }, 2000);
                    load_inv();
                });
                $('#reload-pendings').click(function (event) {
                    event.preventDefault();
                    $(this).children('i').addClass('ani rotate');
                    setTimeout(function () {
                        $('#reload-pendings i').removeClass('ani rotate');
                    }, 2000);
                    load_pend();
                });
            });
            function load_inv() {
                $('#inventory-table td').removeClass('clickable').text('');
                $.getJSON('./inv/api.php', {'p': 'inv', 'u': current_userid, 'w': 'world'}, function (data) {
                    console.log(data);
                    $.each(data.inv, function (index, value) {
                        $el = $('#inventory-table .slot-' + value.slot);
                        itm = '<span class="mc-';
                        if (value.item >= 256) {
                            itm += 'item';
                        } else {
                            itm += 'block';
                        }
                        itm += '"><span class="id-' + value.item + '">&nbsp;</span></span>'
                        $el.addClass('clickable').html(itm);
                        $el.data('slotid', value.slot); //trick to not have to type every data attribute in html..
                        $el.data('name', value.item_name);
                        $el.data('data', value.data);
                        $el.data('damage', value.damage);
                        $el.data('count', value.count);
                        $el.data('id', value.item);
                        $el.data('enchants', value.ench);
                        $el.data('metas', value.meta);

                    });
                });
            }
            function load_pend() {
                $('#pendings-table tbody').empty();
                $.getJSON('./inv/api.php', {'p': 'pendings', 'u': current_userid, 'w': 'world'}, function (data) {
                    console.log(data);
                    $.each(data.pendings, function (index, value) {
                        $el = $('#pendings-table tbody').append('<tr><td class="inventory">' + value.item_name + '</td><td class="inventory">x' + value.count + '</td></tr>');
                        $el.data('name', value.item_name);
                        //$el.data('data', value.data);
                        //$el.data('damage', value.damage);
                        $el.data('count', value.count);
                        //$el.data('id', value.item);

                    });
                });
            }</script>

    <?php
    }
} ?>

</div>
</body>
</html>
