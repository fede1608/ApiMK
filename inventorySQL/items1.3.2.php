<?php
//coded mostly by fede1608 (around 88%)
    function getId($name){
    	global $users,$MySQL;
    	foreach($users as $user){
    		if (strtolower($user['name'])==strtolower($name)) return $user['id'];
    	}
        
        $MySQL->execute("INSERT INTO inventorysql.`inventorysql_users`(`name`, `password`) VALUES (\"".$name."\",\"\")");
        $users = $MySQL->execute("SELECT `id`, `name` FROM inventorysql.`inventorysql_users`");
    	foreach($users as $user){
    			if (strtolower($user['name'])==strtolower($name)) return $user['id'];
    	}
        return -1;
	}
	
	function getItemId($name){
		global $items;
		foreach($items as $id[0] => $item){
			foreach($item as $id[1] => $subitem){
			if($subitem == $name)return $id;
		
			}	
		}
	}
	
	function getIdData($all){
	preg_match("/([0-9]{1,4}):([0-9]{1,5})/", $all, $matches);
	return $matches;
	}
	
	function isBreakable($id){
		switch(true) {
		case (($id>=256)&&($id<=259)):return true;
		case ($id == 261):
		case (($id>=267)&&($id<=279)):
		case (($id>=283)&&($id<=286)):
		case (($id>=290)&&($id<=294)):
		case (($id>=298)&&($id<=317)): return true;
		default: return false;
		}
		}
		
	function addEnchantment($uuid,$eid,$level){
		$archivo2=fopen("Logs/WebShopDebugLogs.txt","a");
		fputs($archivo2,"----AddEnchantment----\n");
		global $MySQL,$enchant,$tableenchantments;
		$enchants = $MySQL->execute("SELECT *  FROM `".$tableenchantments."` WHERE `id` = \"".$uuid."\"");
		$add = $MySQL->execute("INSERT INTO `". $tableenchantments ."`(`id`, `ench_index`, `ench`, `level`, `is_backup`) VALUES (\"".$uuid."\", ".getEnchantIndex($enchants).",".$eid.",".$level.",0);");
		fputs($archivo2, date(DATE_RFC822)." INSERT INTO `". $tableenchantments ."`(`id`, `ench_index`, `ench`, `level`, `is_backup`) VALUES (\"".$uuid."\", ".getEnchantIndex($enchants).",".$eid.",".$level.",0);\n");
		fputs($archivo2,$add);
		fputs($archivo2,"\n");
		fclose($archivo2);
		//if($add) echo 'Added Enchantment or whatever, I\' m just an echo';
		//else echo 'error SQL';
	}
		
		function getEnchantIndex($enc){
		if($enc == false) return 0;
		if(isset($enc[2])) return 3;
		if(isset($enc[1])) return 2;
		if(isset($enc[0])) return 1;
		
		
		
		//si hay 4 hechizos reemplaza el ultimo
		if(isset($enc[3])) return 3;
		}
		
		function addItem($uuid,$name,$itemid,$itemdata,$amount) {
		$archivo2=fopen("Logs/WebShopDebugLogs.txt","a");
		fputs($archivo2,"----AddItem----\n");
			global $MySQL,$tablependings;					
			$give = $MySQL->execute("INSERT INTO `". $tablependings ."`(`id`, `owner`, `world`, `item`, `data`, `damage`, `count`) VALUES (\"".$uuid."\", '".getID($name)."', '', '". $itemid ."', '".$itemdata."', '0', '". $amount ."');");
		fputs($archivo2, date(DATE_RFC822)." INSERT INTO `". $tablependings ."`(`id`, `owner`, `world`, `item`, `data`, `damage`, `count`) VALUES (\"".$uuid."\", '".getID($name)."', '', '". $itemid ."', '".$itemdata."', '0', '". $amount ."');\n");
		fputs($archivo2,$give);
		fputs($archivo2,"\n");
		fclose($archivo2);
		// if($give) echo 'Added item or whatever, I\' m just an echo';
		// else echo 'error SQL';
		}
		function addMeta($uuid,$key,$value) {
		$archivo2=fopen("Logs/WebShopDebugLogs.txt","a");
		fputs($archivo2,"----AddMeta----\n");
			global $MySQL,$tablependings;					
			$give = $MySQL->execute("INSERT INTO `inventorysql_meta`(`id`, `key`, `value`, `is_backup`) VALUES (\"".$uuid."\", '".$key."',\"".$value."\", '0');");
		fputs($archivo2, date(DATE_RFC822)." INSERT INTO `inventorysql_meta`(`id`, `key`, `value`, `is_backup`) VALUES (\"".$uuid."\", '".$key."',\"".$value."\", '0');\n");
		fputs($archivo2,$give);
		fputs($archivo2,"\n");
		fclose($archivo2);
			// echo "INSERT INTO `inventorysql_meta`(`id`, `key`, `value`, `is_backup`) VALUES (\"".$uuid."\", '".$key."',\"".$value."\", '0');";
		//if($give) echo 'Added item or whatever, I\' m just an echo';
		//else echo 'error SQL';
		}
		function addPotion($uuid,$name,$itemid,$itemdata,$amount) {
			global $MySQL,$tablependings;					
			$give = $MySQL->execute("INSERT INTO `". $tablependings ."`(`id`, `owner`, `world`, `item`, `data`, `damage`, `count`) VALUES (\"".$uuid."\", '".getID($name)."', '', '". $itemid ."', '5', '".$itemdata."', '". $amount ."');");
		//if($give) echo 'Added item or whatever, I\' m just an echo';
		//else echo 'error SQL';
		}
		function addEgg($uuid,$name,$itemid,$itemdata,$amount) {
			global $MySQL,$tablependings;					
			$give = $MySQL->execute("INSERT INTO `". $tablependings ."`(`id`, `owner`, `world`, `item`, `data`, `damage`, `count`) VALUES (\"".$uuid."\", '".getID($name)."', '', '". $itemid ."', '".$itemdata."', '".$itemdata."', '". $amount ."');");
		//if($give) echo 'Added item or whatever, I\' m just an echo';
		//else echo 'error SQL';
		}
	function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
	}
	
	
/* items names */
$items[] = array();
$items_version = "1.4.5";

/************************************
 * Setup blocks and items name here
 * Minecraft 1.1
/************************************/

$items[0][0] = 'Air';
$items[1][0] = 'Stone';
$items[2][0] = 'Grass';
$items[3][0] = 'Dirt';
$items[4][0] = 'Cobblestone';

$items[5][0] = 'Wooden Plank (Oak)';
$items[5][1] = 'Wooden Plank (Pine)';
$items[5][2] = 'Wooden Plank (Birch)';
$items[5][3] = 'Wooden Plank (Jungle)';

$items[6][0] = 'Sapling (Oak)';
$items[6][1] = 'Sapling (Pine)';
$items[6][2] = 'Sapling (Birch)';
$items[6][3] = 'Sapling (Jungle)';

$items[7][0] = 'Bedrock';
$items[8][0] = 'Water';
$items[9][0] = 'Stationary Water';
$items[10][0] = 'Lava';
$items[11][0] = 'Stationary Lava';
$items[12][0] = 'Sand';
$items[13][0] = 'Gravel';
$items[14][0] = 'Gold Ore';
$items[15][0] = 'Iron Ore';
$items[16][0] = 'Coal Ore';

$items[17][0] = 'Log (Oak)';
$items[17][1] = 'Log (Pine)';
$items[17][2] = 'Log (Birch)';
$items[17][3] = 'Log (Jungle)';

$items[18][0] = 'Leaves (Oak)';
$items[18][1] = 'Leaves (Pine)';
$items[18][2] = 'Leaves (Birch)';
$items[18][3] = 'Leaves (Jungle)';

$items[19][0] = 'Sponge';
$items[20][0] = 'Glass';
$items[21][0] = 'Lapis Ore';
$items[22][0] = 'Lapis Block';
$items[23][0] = 'Dispenser';

$items[24][0] = 'Sandstone';
$items[24][1] = 'Sandstone (Chiseled)';
$items[24][2] = 'Sandstone (Smooth)';

$items[25][0] = 'Note Block';
$items[26][0] = 'Bed Block';
$items[27][0] = 'Powered Rail';
$items[28][0] = 'Detector Rail';
$items[29][0] = 'Piston Sticky Base';
$items[30][0] = 'Web';

$items[31][0] = 'Tall Grass(Dead Shrub)';
$items[31][1] = 'Tall Grass';
$items[31][2] = 'Tall Grass (Fern)';

$items[32][0] = 'Dead Bush';
$items[33][0] = 'Piston Base';
$items[34][0] = 'Piston Extension';

$items[35][0] = 'Wool';
$items[35][1] = 'Orange Wool';
$items[35][2] = 'Magenta Wool';
$items[35][3] = 'Light Blue Wool';
$items[35][4] = 'Yellow Wool';
$items[35][5] = 'Lime Wool';
$items[35][6] = 'Pink Wool';
$items[35][7] = 'Gray Wool';
$items[35][8] = 'Light Gray Wool';
$items[35][9] = 'Cyan Wool';
$items[35][10] = 'Purple Wool';
$items[35][11] = 'Blue Wool';
$items[35][12] = 'Brown Wool';
$items[35][13] = 'Green Wool';
$items[35][14] = 'Red Wool';
$items[35][15] = 'Black Wool';

$items[36][0] = 'Piston Moving Piece';
$items[37][0] = 'Dandelion (Yellow Flower)';
$items[38][0] = 'Rose';
$items[39][0] = 'Brown Mushroom';
$items[40][0] = 'Red Mushroom';
$items[41][0] = 'Gold Block';
$items[42][0] = 'Iron Block';

$items[43][0] = 'Stone Slab (Double)';
$items[43][1] = 'Sandstone Slab (Double)';
$items[43][2] = 'Wooden Slab (Double)';
$items[43][3] = 'Cobblestone Slab (Double)';
$items[43][4] = 'Brick Slab (Double)';
$items[43][5] = 'Stone Brick Slab (Double)';

$items[44][0] = 'Stone Slab';
$items[44][1] = 'Sandstone Slab';
$items[44][2] = 'Wooden Slab';
$items[44][3] = 'Cobblestone Slab';
$items[44][4] = 'Brick Slab';
$items[44][5] = 'Stone Brick Slab';

$items[45][0] = 'Brick';
$items[46][0] = 'Tnt';
$items[47][0] = 'Bookshelf';
$items[48][0] = 'Mossy Cobblestone';
$items[49][0] = 'Obsidian';
$items[50][0] = 'Torch';
$items[51][0] = 'Fire';
$items[52][0] = 'Mob Spawner';
$items[53][0] = 'Wood Stairs';
$items[54][0] = 'Chest';
$items[55][0] = 'Redstone Wire';
$items[56][0] = 'Diamond Ore';
$items[57][0] = 'Diamond Block';
$items[58][0] = 'Workbench';
$items[59][0] = 'Crops';
$items[60][0] = 'Soil';
$items[61][0] = 'Furnace';
$items[62][0] = 'Burning Furnace';
$items[63][0] = 'Sign Post';
$items[64][0] = 'Wooden Door';
$items[65][0] = 'Ladder';
$items[66][0] = 'Rails';
$items[67][0] = 'Cobblestone Stairs';
$items[68][0] = 'Wall Sign';
$items[69][0] = 'Lever';
$items[70][0] = 'Stone Plate';
$items[71][0] = 'Iron Door Block';
$items[72][0] = 'Wood Plate';
$items[73][0] = 'Redstone Ore';
$items[74][0] = 'Glowing Redstone Ore';
$items[75][0] = 'Redstone Torch Off';
$items[76][0] = 'Redstone Torch On';
$items[77][0] = 'Stone Button';
$items[78][0] = 'Snow';
$items[79][0] = 'Ice';
$items[80][0] = 'Snow Block';
$items[81][0] = 'Cactus';
$items[82][0] = 'Clay';
$items[83][0] = 'Sugar Cane Block';
$items[84][0] = 'Jukebox';
$items[85][0] = 'Fence';
$items[86][0] = 'Pumpkin';
$items[87][0] = 'Netherrack';
$items[88][0] = 'Soul Sand';
$items[89][0] = 'Glowstone';
$items[90][0] = 'Portal';
$items[91][0] = 'Jack O Lantern';
$items[92][0] = 'Cake Block';
$items[93][0] = 'Diode Block Off';
$items[94][0] = 'Diode Block On';
$items[95][0] = 'Locked Chest';
$items[96][0] = 'Trap Door';

$items[97][0] = 'Silverfish Stone';
$items[97][1] = 'Silverfish Cobblestone';
$items[97][2] = 'Silverfish Stone Brick';

$items[98][0] = 'Stone Brick';
$items[98][1] = 'Mossy Stone Brick';
$items[98][2] = 'Cracked Stone Brick';
$items[98][3] = 'Chiseled Stone Brick';

$items[99][0] = 'Huge Mushroom 1';
$items[100][0] = 'Huge Mushroom 2';
$items[101][0] = 'Iron Fence';
$items[102][0] = 'Thin Glass';
$items[103][0] = 'Melon Block';
$items[104][0] = 'Pumpkin Stem';
$items[105][0] = 'Melon Stem';
$items[106][0] = 'Vine';
$items[107][0] = 'Fence Gate';
$items[108][0] = 'Brick Stairs';
$items[109][0] = 'Smooth Stairs';
$items[110][0] = 'Mycel';
$items[111][0] = 'Water Lily';
$items[112][0] = 'Nether Brick';
$items[113][0] = 'Nether Fence';
$items[114][0] = 'Nether Brick Stairs';
$items[115][0] = 'Nether Warts';
$items[116][0] = 'Enchantment Table';
$items[117][0] = 'Brewing Stand';
$items[118][0] = 'Cauldron';
$items[119][0] = 'Ender Portal';
$items[120][0] = 'Ender Portal Frame';
$items[121][0] = 'Ender Stone';
$items[122][0] = 'Dragon Egg';
$items[123][0] = 'Redstone Lamp';
$items[124][0] = 'Redstone Lamp (On)';

$items[125][0] = 'Oak-Wood Slab (Double)';
$items[125][1] = 'Pine-Wood Slab (Double)';
$items[125][2] = 'Birch-Wood Slab (Double)';
$items[125][3] = 'Jungle-Wood Slab (Double)';

$items[126][0] = 'Oak-Wood Slab';
$items[126][1] = 'Pine-Wood Slab';
$items[126][2] = 'Birch-Wood Slab';
$items[126][3] = 'Jungle-Wood Slab';

$items[127][0] = 'Cocoa Plant';
$items[128][0] = 'Sandstone Stairs';
$items[129][0] = 'Emerald Ore';
$items[130][0] = 'Ender Chest';
$items[131][0] = 'Tripwire Hook';
$items[132][0] = 'Tripwire';
$items[133][0] = 'Block of Emerald';
$items[134][0] = 'Wooden Stairs (Pine)';
$items[135][0] = 'Wooden Stairs (Birch)';
$items[136][0] = 'Wooden Stairs (Jungle)';
$items[137][0] = 'Command Block';
$items[138][0] = 'Beacon Block';

$items[139][0] = 'Cobblestone Wall';
$items[139][1] = 'Mossy Cobblestone Wall';
//1.4
$items[140][0] = 'Flower Pot (Block)';
$items[141][0] = 'Carrots (Crop)';
$items[142][0] = 'Potatoes (Crop)';
$items[143][0] = 'Button (Wood)';

//damageable
$items[256][0] = 'Iron Shovel';
$items[257][0] = 'Iron Pickaxe';
$items[258][0] = 'Iron Axe';
$items[259][0] = 'Flint And Steel';

$items[260][0] = 'Apple';
//damageable
$items[261][0] = 'Bow';

$items[262][0] = 'Arrow';

$items[263][0] = 'Coal';
$items[263][1] = 'Charcoal';

$items[264][0] = 'Diamond';
$items[265][0] = 'Iron Ingot';
$items[266][0] = 'Gold Ingot';
//damageable
$items[267][0] = 'Iron Sword';
$items[268][0] = 'Wood Sword';
$items[269][0] = 'Wood Shovel';
$items[270][0] = 'Wood Pickaxe';
$items[271][0] = 'Wood Axe';
$items[272][0] = 'Stone Sword';
$items[273][0] = 'Stone Shovel';
$items[274][0] = 'Stone Pickaxe';
$items[275][0] = 'Stone Axe';
$items[276][0] = 'Diamond Sword';
$items[277][0] = 'Diamond Shovel';
$items[278][0] = 'Diamond Pickaxe';
$items[279][0] = 'Diamond Axe';

$items[280][0] = 'Stick';
$items[281][0] = 'Bowl';
$items[282][0] = 'Mushroom Soup';

//damageable
$items[283][0] = 'Gold Sword';
$items[284][0] = 'Gold Shovel';
$items[285][0] = 'Gold Pickaxe';
$items[286][0] = 'Gold Axe';

$items[287][0] = 'String';
$items[288][0] = 'Feather';
$items[289][0] = 'Sulphur';
//damageable
$items[290][0] = 'Wood Hoe';
$items[291][0] = 'Stone Hoe';
$items[292][0] = 'Iron Hoe';
$items[293][0] = 'Diamond Hoe';
$items[294][0] = 'Gold Hoe';

$items[295][0] = 'Seeds';
$items[296][0] = 'Wheat';
$items[297][0] = 'Bread';
//damageable armor
$items[298][0] = 'Leather Helmet';
$items[299][0] = 'Leather Chestplate';
$items[300][0] = 'Leather Leggings';
$items[301][0] = 'Leather Boots';
$items[302][0] = 'Chainmail Helmet';
$items[303][0] = 'Chainmail Chestplate';
$items[304][0] = 'Chainmail Leggings';
$items[305][0] = 'Chainmail Boots';
$items[306][0] = 'Iron Helmet';
$items[307][0] = 'Iron Chestplate';
$items[308][0] = 'Iron Leggings';
$items[309][0] = 'Iron Boots';
$items[310][0] = 'Diamond Helmet';
$items[311][0] = 'Diamond Chestplate';
$items[312][0] = 'Diamond Leggings';
$items[313][0] = 'Diamond Boots';
$items[314][0] = 'Gold Helmet';
$items[315][0] = 'Gold Chestplate';
$items[316][0] = 'Gold Leggings';
$items[317][0] = 'Gold Boots';

$items[318][0] = 'Flint';
$items[319][0] = 'Pork';
$items[320][0] = 'Grilled Pork';
$items[321][0] = 'Painting';
$items[322][0] = 'Golden Apple';
$items[323][0] = 'Sign';
$items[324][0] = 'Wood Door';
$items[325][0] = 'Bucket';
$items[326][0] = 'Water Bucket';
$items[327][0] = 'Lava Bucket';
$items[328][0] = 'Minecart';
$items[329][0] = 'Saddle';
$items[330][0] = 'Iron Door';
$items[331][0] = 'Redstone';
$items[332][0] = 'Snow Ball';
$items[333][0] = 'Boat';
$items[334][0] = 'Leather';
$items[335][0] = 'Milk Bucket';
$items[336][0] = 'Clay Brick';
$items[337][0] = 'Clay Ball';
$items[338][0] = 'Sugar Cane';
$items[339][0] = 'Paper';
$items[340][0] = 'Book';
$items[341][0] = 'Slime Ball';
$items[342][0] = 'Storage Minecart';
$items[343][0] = 'Powered Minecart';
$items[344][0] = 'Egg';
$items[345][0] = 'Compass';
$items[346][0] = 'Fishing Rod';
$items[347][0] = 'Watch';
$items[348][0] = 'Glowstone Dust';
$items[349][0] = 'Raw Fish';
$items[350][0] = 'Cooked Fish';

$items[351][0] = 'Ink Sack';
$items[351][1] = 'Rose Red Dye';
$items[351][2] = 'Cactus Green Dye';
$items[351][3] = 'Cocoa Bean';
$items[351][4] = 'Lapis Lazuli';
$items[351][5] = 'Purple Dye';
$items[351][6] = 'Cyan Dye';
$items[351][7] = 'Light Gray Dye';
$items[351][8] = 'Gray Dye';
$items[351][9] = 'Pink Dye';
$items[351][10] = 'Lime Dye';
$items[351][11] = 'Dandelion Yellow Dye';
$items[351][12] = 'Light Blue Dye';
$items[351][13] = 'Magenta Dye';
$items[351][14] = 'Orange Dye';
$items[351][15] = 'Bone Meal';

$items[352][0] = 'Bone';
$items[353][0] = 'Sugar';
$items[354][0] = 'Cake';
$items[355][0] = 'Bed';
$items[356][0] = 'Diode';
$items[357][0] = 'Cookie';
$items[358][0] = 'Map';
$items[359][0] = 'Shears';
$items[360][0] = 'Melon';
$items[361][0] = 'Pumpkin Seeds';
$items[362][0] = 'Melon Seeds';
$items[363][0] = 'Raw Beef';
$items[364][0] = 'Cooked Beef';
$items[365][0] = 'Raw Chicken';
$items[366][0] = 'Cooked Chicken';
$items[367][0] = 'Rotten Flesh';
$items[368][0] = 'Ender Pearl';
$items[369][0] = 'Blaze Rod';
$items[370][0] = 'Ghast Tear';
$items[371][0] = 'Gold Nugget';
$items[372][0] = 'Nether Stalk';
//potions
$items[373][0] = 'Water Bottle';
$items[373][16] = '';
$items[373][32] = '';
$items[373][64] = '';
$items[373][8193] = 'Regeneration Potion (0:45)';
$items[373][8194] = 'Swiftness Potion (3:00)';
$items[373][8195] = 'Fire Resistance Potion (3:00)';
$items[373][8196] = 'Poison Potion (0:45)';
$items[373][8197] = 'Healing Potion';
$items[373][8198] = 'Night Vision Potion (3:00)';
$items[373][8200] = 'Weakness Potion (1:30)';
$items[373][8201] = 'Strength Potion (3:00)';
$items[373][8202] = 'Slowness Potion (1:30)';
$items[373][8204] = 'Harming Potion';
$items[373][8206] = 'Invisibility Potion (3:00)';
$items[373][8225] = 'Regeneration Potion II (0:22)';
$items[373][8226] = 'Swiftness Potion II (1:30)';
$items[373][8228] = 'Poison Potion II (0:22)';
$items[373][8229] = 'Healing Potion II';
$items[373][8233] = 'Strength Potion II (1:30)';
$items[373][8236] = 'Harming Potion II';
$items[373][8257] = 'Regeneration Potion (2:00)';
$items[373][8258] = 'Swiftness Potion (8:00)';
$items[373][8259] = 'Fire Resistance Potion (8:00)';
$items[373][8260] = 'Poison Potion (2:00)';
$items[373][8262] = 'Night Vision Potion (8:00)';
$items[373][8264] = 'Weakness Potion (4:00)';
$items[373][8265] = 'Strength Potion (8:00)';
$items[373][8266] = 'Slowness Potion (4:00)';
$items[373][8270] = 'Invisibility Potion (8:00)';
$items[373][16385] = 'Regeneration Splash (0:33)';
$items[373][16386] = 'Swiftness Splash (2:15)';
$items[373][16387] = 'Fire Resistance Splash (2:15)';
$items[373][16388] = 'Poison Splash (0:33)';
$items[373][16389] = 'Healing Splash';
$items[373][16390] = 'Night Vision Splash (2:15)';
$items[373][16392] = 'Weakness Splash (1:07)';
$items[373][16393] = 'Strength Splash (2:15)';
$items[373][16394] = 'Slowness Splash (1:07)';
$items[373][16396] = 'Harming Splash';
$items[373][16398] = 'Invisibility Splash (2:15)';
$items[373][16417] = 'Regeneration Splash II (0:16)';
$items[373][16418] = 'Swiftness Splash II (1:07)';
$items[373][16420] = 'Poison Splash II (0:16)';
$items[373][16421] = 'Healing Splash II';
$items[373][16425] = 'Strength Splash II (1:07)';
$items[373][16428] = 'Harming Splash II';
$items[373][16449] = 'Regeneration Splash (1:30)';
$items[373][16450] = 'Swiftness Splash (6:00)';
$items[373][16451] = 'Fire Resistance Splash (6:00)';
$items[373][16452] = 'Poison Splash (1:30)';
$items[373][16454] = 'Night Vision Splash (6:00)';
$items[373][16456] = 'Weakness Splash (3:00)';
$items[373][16457] = 'Strength Splash (6:00)';
$items[373][16458] = 'Slowness Splash (3:00)';
$items[373][16462] = 'Invisibility Splash (6:00)';


$items[374][0] = 'Glass Bottle';
$items[375][0] = 'Spider Eye';
$items[376][0] = 'Fermented Spider Eye';
$items[377][0] = 'Blaze Powder';
$items[378][0] = 'Magma Cream';
$items[379][0] = 'Brewing Stand Item';
$items[380][0] = 'Cauldron Item';
$items[381][0] = 'Eye Of Ender';
$items[382][0] = 'Speckled Melon';
$items[383][0] = 'Spawn Egg';
$items[383][50] = 'Spawn Egg(Creeper)';
$items[383][51] = 'Spawn Egg(Skeleton)';
$items[383][52] = 'Spawn Egg(Spider)';
$items[383][54] = 'Spawn Egg(Zombie)';
$items[383][55] = 'Spawn Egg(Slime)';
$items[383][56] = 'Spawn Egg(Ghast)';
$items[383][57] = 'Spawn Egg(Zombie Pigmen)';
$items[383][58] = 'Spawn Egg(Endermen)';
$items[383][59] = 'Spawn Egg(Cave Spider)';
$items[383][60] = 'Spawn Egg(Silverfish)';
$items[383][61] = 'Spawn Egg(Blaze)';
$items[383][62] = 'Spawn Egg(Magma Cube)';$items[383][65] = 'Spawn Egg(Bat)';$items[383][66] = 'Spawn Egg(Witch)';
$items[383][90] = 'Spawn Egg(Pig)';
$items[383][91] = 'Spawn Egg(Sheep)';
$items[383][92] = 'Spawn Egg(Cow)';
$items[383][93] = 'Spawn Egg(Chicken)';
$items[383][94] = 'Spawn Egg(Squid)';
$items[383][95] = 'Spawn Egg(Wolf)';
$items[383][96] = 'Spawn Egg(Moshroom)';
$items[383][98] = 'Spawn Egg(Ocelot)';
$items[383][120] = 'Spawn Egg(Villager)';

$items[384][0] = 'Bottle Exp';
$items[385][0] = 'Fire Charge';
$items[386][0] = 'Book and Quill';
$items[387][0] = 'Written Book';
$items[388][0] = 'Emerald';

//1.4
$items[389][0] = 'Item Frame';
$items[390][0] = 'Flower Pot';
$items[391][0] = 'Carrots';
$items[392][0] = 'Potato';
$items[393][0] = 'Baked Potato';
$items[394][0] = 'Poisonous Potato';
$items[395][0] = 'Empty Map';
$items[396][0] = 'Golden Carrot';

$items[2256][0] = 'Gold Record';
$items[2257][0] = 'Green Record';
$items[2258][0] = 'Record 3';
$items[2259][0] = 'Record 4';
$items[2260][0] = 'Record 5';
$items[2261][0] = 'Record 6';
$items[2262][0] = 'Record 7';
$items[2263][0] = 'Record 8';
$items[2264][0] = 'Record 9';
$items[2265][0] = 'Record 10';
$items[2266][0] = 'Record 11';

//armor
$enchant[0] = 'Protecion';
$enchant[1] = 'Fire Protection';
$enchant[2] = 'Feather Falling';
$enchant[4] = 'Projectile Protection';
$enchant[7] = 'Thorns';
//boots
$enchant[3] = 'Blast Protection';
//casco
$enchant[5] = 'Respiration';
$enchant[6] = 'Aqua Affinity';
//sword
$enchant[16] = 'Sharpness';
$enchant[17] = 'Smite';
$enchant[18] = 'Bane of Arthropods';
$enchant[19] = 'Knockback';
$enchant[20] = 'Fire Aspect';
$enchant[21] = 'Looting';
//tools
$enchant[32] = 'Efficiency';
$enchant[33] = 'Silk Touch';
$enchant[34] = 'Unbreaking';
$enchant[35] = 'Fortune';
//Bow
$enchant[48] = 'Power';
$enchant[49] = 'Punch';
$enchant[50] = 'Flame';
$enchant[51] = 'Infinity';

$maxlevel[0] = 4;
$maxlevel[1] = 4;
$maxlevel[2] = 4;
$maxlevel[3] = 4;
$maxlevel[4] = 4;
$maxlevel[5] = 3;
$maxlevel[6] = 1;$maxlevel[7] = 1;
$maxlevel[16] = 5;
$maxlevel[17] = 5;
$maxlevel[18] = 5;
$maxlevel[19] = 2;
$maxlevel[20] = 2;
$maxlevel[21] = 3;
$maxlevel[32] = 5;
$maxlevel[33] = 1;
$maxlevel[34] = 3;
$maxlevel[35] = 3;
$maxlevel[48] = 4;
$maxlevel[49] = 2;
$maxlevel[50] = 1;
$maxlevel[51] = 1;

