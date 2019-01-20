# Product_for_cram_school

業務管理＆学習補助システム

＜概要＞
1.	プロジェクトの背景および目的
3年間塾講師のアルバイトをした経験をもとに業務量の多い学習塾の業務を効率化したいと考えた。業務軽減だけでなく、学習支援の機能も付け加えて一つのシステムの中で利用できるようにした。

2.	想定ユーザー
想定ユーザー数：450人程度。
メインユーザーは、一つの学習塾が利用すると考えて、塾の職員、生徒、およびその保護者を想定する。

3.	用途
想定した用途は次の通りである。
•	塾の予約管理、状況の確認。
•	責任者、講師、生徒、保護者それぞれでのログイン
•	室長から講師へ業務連絡。
•	出席簿・出勤簿の入力
•	生徒、保護者へのお知らせ機能
•	指導報告書を保護者宛てに送る
•	人気講師の授業動画を配信する
•	保護者の監視機能。生徒と先生のやり取りを確認できる。抑止につながる。
•	生徒から講師への質問機能。写真で分からない問題を送るなどできるように。

4.  必要となった機能。
•	カレンダー表示。および、データの挿入、時間設定。
•	メール認証付きログイン機能
•	掲示板機能
•	画像、動画のアップロード機能

6. 開発環境
•言語：PHP5.2.4
•データベース：MySQL（PDO）

5.	その他
•	セキュリティ対策として、パスワードのハッシュ化を行う。
•	アカウントの種類によってアクセスできる情報を制限する。

＜構成＞
データベース接続：db.php

会員登録

    仮登録：registration_mail_form.php → registration_mail_check.php
    本登録：registration_form.php → registration_check.php → registration_insert.php
  
ログイン 
    
    login_form.php → login_check.php → 4種類のアカウントへ
  
ログアウト
　
    logout.php
  
アカウントの種類

    生徒用：login_admin_student.php
    講師用：login_admin_teacher.php
    保護者用：login_admin_parent.php
    責任者用:login_admin_manager.php
    
 責任者専用機能
 
    登録情報の確認：check_registered_information.php
    アカウント削除：operate_registered_information.php
    授業コードの設定：code_set.php
    保護者会の予約状況の確認：check_interview_registration.php
    
    
講師専用機能
     
     出席・出勤記録：attendence_of_students.php, attendence_of_teachers.php

保護者専用機能
     
      カレンダーの表示：calender.php
      日付確認・登録：skedulu_registration.php
     
画像・動画

  　　データベースへ格納：media_insert.php
    　表示：import_media.php
    
お知らせ

     作成：announce.php
     表示：notification.php
     削除：delete_notice.php
     
掲示板機能

  　　message.php(read_message.phpは読めるが書き込みはできないもの。)
       　
   
