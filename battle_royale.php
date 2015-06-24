<?php
class Player {
	public $name = "";
  	public $manna = 40;
  	public $blood = 100;
  		
  		public function __construct($name) {
   		$this->name = $name;
  		}
};	

class BattleRoyale{
	private $players = [];

	public function run() {
    $this->print_welcome_and_description();
    echo "# Current Player:\n";

    if(count($this->players) == 0) {
      echo "#   -\n";
    }
    else {
      for($i = 0; $i < count($this->players); $i++) {
        echo "#   " . $this->players[$i]->name . "\n";
      }
    }

    echo "# *Max Player 2\n";
    echo "#-----------------------------------------------------------\n";
    echo "Command: ";
    $this->wait_for_command();
  }

  private function new_character() {
    $this->print_welcome_and_description();
    echo "# Current Player:\n";

    if(count($this->players) == 0) {
      echo "#   -\n";
    }
    else {
      for($i = 0; $i < count($this->players); $i++) {
        echo "#   " . $this->players[$i]->name . "\n";
      }
    }

    if(count($this->players) > 1) {
      echo "Maksimal player adalah 2 player\n";
      $this->press_any_key_and_continue();
    }
    else {
      echo "Masukan nama player: ";
      $player_name = $this->get_input_from_user();;
      $player = new Player($player_name);
      array_push($this->players, $player);
    }

    $this->run();
  }

  private function start_game() {
    if(count($this->players) < 2) {
      echo "Belum ada player, silakan buat player terlebih dahulu\n";
      $this->press_any_key_and_continue();
      $this->run();
    }

    $game_running = true;
    $attack = null;
    $defend = null;
    $battle_num = 1;

    $this->print_welcome();

    while($game_running) {
      echo "Battle #" . $battle_num . " Start\n";

      echo "Siapa yang akan menyerang: ";
      $input = $this->get_input_from_user();
      $attack = $this->find_player_by_name($input);

      if($attack == null) continue;

      echo "Siapa yang akan diserang: ";
      $input = $this->get_input_from_user();
      $defend = $this->find_player_by_name($input);

      if($defend == null) continue;

      if($defend != null || $attack != null) {
        $game_running = $this->do_battle($attack, $defend);
        $battle_num++;
      }
    }

    exit();
  }

	private function do_battle($attacker, $defender) {
    $attacker->manna -= 5;  
    $defender->blood -= 20; 

    echo "Description:\n";
    echo $attacker->name . ": manna = " . $attacker->manna . " blood = " . $attacker->blood . "\n";
    echo $defender->name . ": manna = " . $defender->manna . " blood = " . $defender->blood . "\n";

    $this->press_any_key_and_continue();

    if($attacker->manna <= 0 || $attacker->blood <= 0) {
      $this->print_game_over($defender->name);
    }
    elseif($defender->manna <= 0 || $defender->blood <= 0) {
      $this->print_game_over($attacker->name);
    }
    else {
      echo "#-------------------------------------------------\n";
      return true;
    }

    return false;
  }

  private function find_player_by_name($name) {
    for($i = 0; $i < count($this->players); $i++) {
      if($this->players[$i]->name == $name)
        return $this->players[$i];
    }

    echo "Player tidak ditemukan, coba lagi\n";
    $this->press_any_key_and_continue();
    return null;
  }

  private function wait_for_command() {
    $input = $this->get_input_from_user();;

    switch($input) {
      case "exit":
        echo "Goodbye...\n";
        exit();
        break;

      case "new":
        $this->new_character();
        break;

      case "start":
        $this->start_game();
        break;

      default:
        $this->run();
    }
  }

  private function clear_screen() {
    if(PHP_OS == 'WINNT')
      for($i = 0; $i < 50; $i++) echo "\r\n";
    else
      system("clear") || sysyem("cls");
  }

   private function print_game_over($winner_name) {
    echo "\n";
    echo "--------------------------------------------------\n";
    echo "          GAME OVER\n";
    echo "--------------------------------------------------\n";
    echo "Selamat! Pemenangnya adalah: " . $winner_name . "\n";
  }

   private function print_welcome_and_description() {
    $this->clear_screen();
    $this->print_welcome();
    echo "Description:\n";
    echo "1. new = membuat karakter\n";
    echo "2. start = memulai pertarungan\n";
    echo "3. exit = keluar\n";
    echo "--------------------------------------------------\n";
  }

   private function print_welcome() {
    $this->clear_screen();
    echo "=========================================================\n";
    echo "Welcome to Battle Arena\n";
    echo "-----------------------\n";
  }

   private function press_any_key_and_continue() {
    echo "Tekan enter untuk melanjutkan...";
    $this->get_input_from_user();
    echo "\n";
  }

  private function get_input_from_user() {
    if(PHP_OS == 'WINNT') // on windows
      return stream_get_line(STDIN, 1024, PHP_EOL);
    else
      return readline();
  }

};

$game = new BattleRoyale();
$game->run();

?>
