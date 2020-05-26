# WebRTC Square
SquareはWebRTCを実現するWebアプリケーションです。

# Create new project
新しいプロジェクトを開始するには、下記のようにしてStrangerPHPをcloneしてください。
下記の例では、/home/someone/Project配下にプロジェクト名「square」でプロジェクトを作成します。
$> cd /home/someone/Project/
$> git clone https://github.com/satokadumasa/square.git square

# データベースの作成

$> cd square/
$> cp config/database.config.sample.php config/development//
$> cp config/database.config.sample.php config/staging//
$> cp config/database.config.sample.php config/production//
$> cp config/database.config.sample.php config/test//

次にスキーマの生成です。以下の条件でスキーマを生成するものとします。

HOST:localhost/
Charset:utf8/
user:root/
password:password/
スキーマ名:square/

まず、先ほどコピーして作成したdatabase.config.phpを以下のように編集します。
$> vi config/[ENVIRONMENT]/database.config.php

$default_database = array(/
  'rdb'      => 'mysql',/
  'host'     => 'localhost',/
  'dbname'   => 'mysql',/
  'charset'  => 'utf8',/
  'username' => 'root',/
  'password' => 'password',/
  );

この時点ではスキーマ名(dbname)を自分のプロジェクト用のスキーマ名には変更しないでください。
編集がおわったらdatabase.config.phpを保存して編集を終了してください。

次に下記のコマンドを実行してください。

$> php ./square.php migrate:create:schema localhost:utf8:root:password:square

これで、MySQL上にスキーマ名「square」でスキーマが作成されました。

次に、migration管理テーブルであるmigrationsをsquareスキーマ上に作成します。
下記のコマンドを実行してください。

$> php ./square.php migrate:init

これで、スキーマ、migration管理テーブルが作成されてました。次に、再度database.config.php
を編集してスキーマ名を変更します。

$> vi config/[ENVIRONMENT]/database.config.php

$default_database = array(/
  'rdb'      => 'mysql',/
  'host'     => 'localhost',/
  'dbname'   => 'square',/
  'charset'  => 'utf8',/
  'username' => 'root',/
  'password' => 'password',/
  );

これで、スキーマsquareにアクセスできるようになりました。

# Create scaffold
StrangerPHPは軽量ではありますが、強力なフレームワークです。
その力の一つがscaffold機能です。
Ruby on Railsなどを使ったことのある方ならおなじみの機能ですね。
コマンド一発で、コントローラーからモデルから、migrationファイルからCRUD機能の実現に必要な
実装の骨組みを生成してくれる機能です。では、そのscaffold機能を使ってuser_infosテーブルに読み書き
をしてviewに読み書きした内容を表示するUserInfoControllerとviewファイル一式、そしてuser_infosテーブル
を作成するためのmigrationファイルを作成してみましょう。

生成する内容としては下記の通りです。
id:識別番号
first_name:名
last_name:姓
pref_id:都道府県ID
city:市区町村名
address:町名・番地

下記のコマンドをプロジェクトルートディレクトリ/home/someone/Project/square/で実行してください。

$> php ./square.php -g scaffold user_infos \
first_name:string:64:false:: \
last_name:string:64:false:: \
pref_id:int:8:false:: \
city:string:32:false:: \
address:string:64:false:: \

言うまでもないかと思いますが 「\」は改行をしめすものではなく続きがあることを示す記号です。
実際には（「\」を抜いて）一行で入力してください。

すると、下記のファイルが出来上がります。

コントローラ
/home/someone/Project/square/controllers/UserInfoController.php
モデルクラス
/home/someone/Project/square/models/UserInfo.php
Viewテンプレート
/home/someone/Project/square/views/UserInfo/index.tpl/
/home/someone/Project/square/views/UserInfo/create.tpl/
/home/someone/Project/square/views/UserInfo/edit.tpl/
/home/someone/Project/square/views/UserInfo/show.tpl/
Migrationファイル
/home/someone/Project/square/db/migrate/20170531235959CreateTableUserInfo.php

これで、とりあえずuser_infosテーブルへの読み書きと読み込み結果の表示を行う機能の骨組みは出来上がりました。

# Run Migration
スキーマsquare内をMySQLのクライアントソフトで覗き見るとわかるかと思いますが、
この時点ではまだmigration管理用のmigrationsテーブルしかありません。
Scaffoldで作った機能で読み書きする対象テーブルuser_infosを作成しないと機能は動きません。
テーブル作成のためには下記のコマンドを実行してください。

$> php ./square.php migrate

そうすると、user_infosテーブルが作成されます。

以上で、プロジェクト作成とScaffold、migration機能についての説明は終了です。

なお、StrangerPHPについてのサポートは随時受け付けております。
商用利用、業務プロジェクトでの利用についてはサポートは有料とさせていただいております。
それ以外、個人での非営利プロジェクトでの利用でのサポートをいたします。
お気軽に下記のメールアドレスまでお問い合わせください。

email: sato.kadumasa＠gmail.com

