<?php
session_start();

class Controller_User extends Controller
{

	public function action_select($id)
	{
		if($id <= 0 || $id > 3){
			return "ユーザーは存在しません";
		}
		$_SESSION["user"] = $id;
		if($_SESSION["user"] == 1) $name = "田中太郎";
		if($_SESSION["user"] == 2) $name = "鈴木次郎";
		if($_SESSION["user"] == 3) $name = "山田花子";

		return $name . "に設定しました";
	}
}
