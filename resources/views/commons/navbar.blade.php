<header>
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Microposts</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::check())
                        <li><a href="#">Users</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">My profile</a></li>
                                <li role="separator" class="divider"></li>
                                <li>{!! link_to_route('logout.get', 'Logout') !!}</li>
                            </ul>
                        </li>
                    @else
                        <li>{!! link_to_route('signup.get', 'Signup') !!}</li>
                        <li>{!! link_to_route('login', 'Login') !!}</li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
このあたりで動作確認を行っておいてください。ログインすると、ナビバーがログイン後のものになるはずです。

Auth ファサード
ファサードとは、各クラスのメソッドを扱いやすくしたものです。以下に ‘Auth’ => Illuminate\Support\Facades\Auth::class, とありますが、右側の Illuminate\Support\Facades\Auth::class が、ファサードに登録したいクラスです。通常、Illuminate\Support\Facades\Auth と記述する必要があるのを、 Auth と短く記述して呼び出すことができるようになるというものです。つまりはエイリアス（ニックネームと同じような意味）なのです。

ファサードは、 config/app.php の aliases の中で設定されています。

config/app.php

    'aliases' => [

        'App'       => Illuminate\Support\Facades\App::class,
        'Artisan'   => Illuminate\Support\Facades\Artisan::class,
        'Auth'      => Illuminate\Support\Facades\Auth::class,
        'Blade'     => Illuminate\Support\Facades\Blade::class,
        'Bus'       => Illuminate\Support\Facades\Bus::class,
        'Cache'     => Illuminate\Support\Facades\Cache::class,
        'Config'    => Illuminate\Support\Facades\Config::class,
        'Cookie'    => Illuminate\Support\Facades\Cookie::class,
        'Crypt'     => Illuminate\Support\Facades\Crypt::class,
        'DB'        => Illuminate\Support\Facades\DB::class,
        'Eloquent'  => Illuminate\Database\Eloquent\Model::class,
        'Event'     => Illuminate\Support\Facades\Event::class,
        'File'      => Illuminate\Support\Facades\File::class,
        'Gate'      => Illuminate\Support\Facades\Gate::class,
        'Hash'      => Illuminate\Support\Facades\Hash::class,
        'Input'     => Illuminate\Support\Facades\Input::class,
        'Lang'      => Illuminate\Support\Facades\Lang::class,
        'Log'       => Illuminate\Support\Facades\Log::class,
        'Mail'      => Illuminate\Support\Facades\Mail::class,
        'Password'  => Illuminate\Support\Facades\Password::class,
        'Queue'     => Illuminate\Support\Facades\Queue::class,
        'Redirect'  => Illuminate\Support\Facades\Redirect::class,
        'Redis'     => Illuminate\Support\Facades\Redis::class,
        'Request'   => Illuminate\Support\Facades\Request::class,
        'Response'  => Illuminate\Support\Facades\Response::class,
        'Route'     => Illuminate\Support\Facades\Route::class,
        'Schema'    => Illuminate\Support\Facades\Schema::class,
        'Session'   => Illuminate\Support\Facades\Session::class,
        'Storage'   => Illuminate\Support\Facades\Storage::class,
        'URL'       => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View'      => Illuminate\Support\Facades\View::class,

        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
    ],
実は、今までに、いくつかファサードを利用しています。上記にある通り、 DB ファサードを使った DB::connection() や、 Route ファサードを使ってルーティングを設定、外部ライブラリである Form::open() などです。

参考: ファサード Laravel 5.5
中でも、 Auth ファサードは、ログイン認証に関するメソッドを提供しています。

Auth::check() は現在の閲覧者がログイン中かどうかをチェックし、 Auth::user() はログイン中のユーザを取得できます。これらはナビバーの中でも利用されています。 Auth::check() を使って、ログイン中の場合とログインしていない場合でナビバーの表示を出し分けています。

トップページ
トップページも今後は充実させていくので、ここでは一旦ログインしているかどうかで分岐させるようにしておきましょう。

resources/views/welcome.blade.php

@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <?php $user = Auth::user(); ?>
        {{ $user->name }}
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Microposts</h1>
                {!! link_to_route('signup.get', 'Sign up now!', null, ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection
<?php $user = Auth::user(); ?> と PHP のタグを使えば、 Blade 内で変数に代入することもできます。

7.3 Git
$ git status

$ git diff

$ git add .

$ git commit -m 'user login'
8. その他ユーザ機能
ユーザ登録（作成）や、ログイン認証については既に用意されていた RegisterController や LoginController が担ってくれました。しかし、それ以外のユーザの機能を加えようと思うと、新たに Controller を作成する必要があります。

ここでは、その他のユーザ機能を作成していきます。

8.1 Model
モデルはそのまま用意されていた User モデルを引き続き利用するので、新規作成するモデルはありません。

8.2 Router
RegisterController が用意していたユーザ登録アクション以外に、下記のアクションを作成していきましょう。

ユーザ一覧(index)
ユーザ詳細(show)
この2つのアクションを RegisterController とは別に UsersController として作成していきます。

ログイン認証付きのルーティング
ユーザの機能はログインしていない状態だと見せたくないので、このルーティングには必ずログイン認証を確認するような措置を取ります。

routes/web.php に下記を追記

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
});
Route::group() でルーティングのグループを作り、その際に ['middleware' => ['auth']] を加えることで、このグループに書かれたルーティングは必ずログイン認証を確認させます。

また、 ['only' => ['index', 'show']] とすることで実装するアクションを絞り込むことが可能です。今回の Controller は2つのアクションだけで良いので、その他は作成しません。

認証ミドルウェア
認証ミドルウェアの handle() は、 ['middleware' => ['auth']] が設定されたルーティングにアクセスされたときに毎回呼ばれるメソッドです。

app/Http/Middleware/RedirectIfAuthenticated.php の handle()

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        return $next($request);
    }
if (Auth::guard($guard)->check()) でログインしているかどうかを判断しています。ログイン認証済みなのに、ログインページにアクセスしようとすると、規定では、 /home にリダイレクトされます。

こちらを以下のように変更しましよう

return redirect('/');
これでトップページにリダイレクトされるようになります。

ユーザに対するそれ以外のアクション
ここでは作成しませんが、ユーザが自分の名前を編集するアクション(edit, update)や、退会アクション(destroy)があっても良いし、更にユーザの登録情報（年齢や自己紹介など）を充実（users テーブルのカラム追加）させても良いと思います。これら、 UsersController に実装すれば良く、ここまで学んだ内容で皆さんも充分実装可能です。カラム追加についてはメッセージボードで出した課題と重複するので課題にはしませんが、復習を兼ねて腕試ししたい方は実装してみてください。その過程で気付くこともあるかも知れません。

8.3 UsersController@index
UsersControllerの作成
$ php artisan make:controller UsersController
Controllerの編集
まずは、 index から実装していきます。

app/Http/Controllers/UsersController.php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', [
            'users' => $users,
        ]);
    }
}
create アクションや store アクションが不要なのは、それらは RegisterController が担ってくれたからです。ここでは index と show のみを実装します。 User に関する Controller が2つある形になります。

View
Gravatar 表示ライブラリをインストール
Gravatar を設定していきましょう。

Gravatar とは、 メールアドレスに対して自分のアバター画像を登録するサービスです。Gravatar を登録しておき、 Gravatar に対応しているサイトでメールアドレスを設定して発言などをすると、そのアバター画像が表示されるようになります。

Gravatar
Microposts も Gravatar に対応し、メールアドレスからアバター画像を表示させます。実際にメールアドレスに対して Gravatar を作成してみるとよくわかるでしょう。

ではインストールしていきます。インストール方法は GitHub にある通りに行います。

laravel-gravatar
composer.json の “require” （カンマに注意）

    "require": {
        "php": ">=7.0.0",
        "fideloper/proxy": "~3.3",
        "laravel/framework": "5.5.*",
        "laravel/tinker": "~1.0",
        "laravelcollective/html": "5.5.*",
        "thomaswelton/laravel-gravatar": "~1.0"
    },
あとは、 Laravel プロジェクトフォルダのトップで、コマンド composer udpate を実行すれば自動的に指定したライブラリがインストールされます。

$ composer update
これで Gravatar ライブラリを使用する準備は完了です。

users.index
ユーザ一覧を表示します。

resources/views/users/index.blade.php

@extends('layouts.app')

@section('content')
    @include('users.users', ['users' => $users])
@endsection
ユーザ一覧はあとでフォロー／フォロワーにも使用するので、1つにまとめておきます。

resources/views/users/users.blade.php

@if (count($users) > 0)
<ul class="media-list">
@foreach ($users as $user)
    <li class="media">
        <div class="media-left">
            <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class="media-body">
            <div>
                {{ $user->name }}
            </div>
            <div>
                <p>{!! link_to_route('users.show', 'View profile', ['id' => $user->id]) !!}</p>
            </div>
        </div>
    </li>
@endforeach
</ul>

@endif
ページネーション
$users = User::all(); として、全ユーザを一気に取得した場合、例えば、1000ユーザいたとしたら、1000ユーザを一気に表示することになり、かなり縦長のページになり、負荷もかかります。

ページネーションとは、例えば、10件ずつなどと表示件数を決めて一覧表示する機能です。

Laravel はデフォルトでページネーション機能をサポートしています。

app/Http/Controllers/UsersController.php

    public function index()
    {
        $users = User::paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }
$users = User::paginate(10); で、10件ずつ取得にしています。ただし、10件ないとページネーションが表示されないので、試しに paginate(1) にして確認しても良いでしょう。

参考: ページネーション - Bootstrap
Controller だけでなく View も追記する必要があります。 {!! $users->render() !!} を追記してください。このコードでページネーションのためのものが表示されます。

resources/views/users/users.blade.php 抜粋

<!-- 中略 -->
@endforeach
</ul>
{!! $users->render() !!}
@endif
参考: ページネーション - Laravel 5.5

ナビバー
users の index を作成したので、ナビバーにあった Users のリンクをつけましょう。

resources/views/commons/navbar.blade.php

                    @if (Auth::check())
                        <li>{!! link_to_route('users.index', 'Users') !!}</li>
8.4 UsersController@show
Controller
UsersController
show では、 $id の引数を利用して、表示すべきユーザを特定します。

app/Http/Controllers/UsersController.php の show アクション

    public function show($id)
    {
        $user = User::find($id);

        return view('users.show', [
            'user' => $user,
        ]);
    }
View
users.show
ここまででは、ユーザ詳細ページでは、ユーザの名前と Gravatar を表示しているだけです。後ほど、Microposts, Followings, Followers を実装していきます。

resources/views/users/show.blade.php

@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
        </aside>
        <div class="col-xs-8">
            <ul class="nav nav-tabs nav-justified">
                <li><a href="#">TimeLine</a></li>
                <li><a href="#">Followings</a></li>
                <li><a href="#">Followers</a></li>
            </ul>
        </div>
    </div>
@endsection
ナビバー
users の show を作成したので、ナビバーにあった My profile のリンクをつけましょう。

resources/views/commons/navbar.blade.php

                            <ul class="dropdown-menu">
                                <li>{!! link_to_route('users.show', 'My profile', ['id' => Auth::id()]) !!}</li>
                                <li role="separator" class="divider"></li>
                                <li>{!! link_to_route('logout.get', 'Logout') !!}</li>
                            </ul>
ここで Auth::id() というクラスメソッドを使いましたが、これはログインユーザのIDを取得することができるメソッドで、Auth::user()->id と同じ動きになります。覚えておきましょう。

8.5 Git
$ git status

$ git diff

$ git add .

$ git commit -m 'user pages'
9. 投稿機能
次は、投稿機能を作成していきます。

9.1 Model
ユーザの投稿を Micropost というモデル名で作成していきます。

一対多の関係
User と Micropost は一対多の関係です。

一対多の関係とは、ある1つの Model インスタンス(A)に対して、複数の Model インスタンス(B, B, …)を保持する関係のことです。例えば、今回の User と Micropost では、1人の User は複数の Micropost をツイートすることが可能(hasMany)であり、 Micropost は 必ず1人の User に所属(belongsTo)することが決まっています。



Model 同士の一対多な関係を見抜くことを甘く考えないようにしてください。今後の Web アプリケーション開発において、 一対多の関係となるリソースを次々と作っていくことになります。ここで一対多の Model の作成方法や扱い方をしっかり抑えておきましょう。

テーブル設計
マイグレーションファイルの作成
$ php artisan make:migration create_microposts_table --create=microposts
database/migrations/年月日時_create_microposts_table.php の up() と down()

    public function up()
    {
        Schema::create('microposts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('content');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('microposts');
    }
外部キー制約
$table->foreign(外部キーを設定するカラム名)->references(制約先のID名)->on(外部キー制約先のテーブル名);
この機能は Laravel の機能ではなく、データベース側の機能です。

外部キー制約は、保存されるテーブルの整合性を高めます。整合性とは、間違ったデータをできるだけ排除できるかという性質です。例えば、ある Micropost が、存在しない User の id で User と接続していたとき、その Micropost はデータベース上には存在しますが、どの User も持っていない宙ぶらりん状態のデータとなり、結果的に表示されなかったりエラーを引き起こすデータになります。外部キー制約は、言い換えれば、単に integer として user_id を定義するよりも、 User と Micropost の接続関係を強化するための機能です。

つまり、外部キー制約は、絶対に必要なものではないですが、間違ったデータは保存されにくくなるという性質のものです。

また、unsigned()は、負の数は許可しないということで、user_id につけることで、-1,-2などの数字がカラムに登録されることを防いでいます。 index()は、インデックスとうもので、テーブルのカラムにつけることで検索速度を高めることができるものです。本などの一番後ろの方にページに索引と呼ばれる 本の中に出てくる用語をあかさたな順に並べて、その用語が出て来るページがまとめられているかと思いますが、インデックスはそれと同じ意味になります。

マイグレーションの実行
$ php artisan migrate
Micropost Model
まず、 Micropost のモデルファイルを作成します。

$ php artisan make:model Micropost
作成したモデルファイルに $fillable を設定しておきましょう。

そして、モデルファイル側でも一対多の関係を表現しておきましょう。 Micropost を持つ User は1人なので、 function user() のように単数形 user でメソッドを定義します。中身は return $this->belongsTo(User::class) とします。

app/Micropost.php

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
これで、 Micropost のインスタンスが所属している唯一の User を取得することができます。 $micropost->user()->first() もしくは簡単に $micropost->user で取得できます。

User Model
User モデルファイルも一対多を表現しておきます。 User から Micropost をみたとき、複数存在するので、 function microposts() のように複数形 microposts でメソッドを定義します。中身は return $this->hasMany(Micropost::class); とします。

app/User.php 追記分

    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
同様に、 User のインスタンスが自分の Microposts を取得することができます。 $user->microposts()->all() もしくは簡単に $user->microposts で取得できます。

tinker で投稿を作成
Laravelは、一対多などの関係に新しいモデルを追加するための便利なメソッドを用意しています。例えば、 User モデルに関係する新しい Micropost を挿入するために create メソッドを使えます。このメソッドは、引数で受け取った連想配列をもとに、モデルを作成しデータベースへ挿入します。

※ 参考資料：createメソッド（Eloquent - Laravel 5.5 ドキュメント）

投稿の作成は $user->microposts()->create(['content' => 'micropost test']) のように操作します。

>>> use App\User

>>> use App\Micropost

>>> $user = User::first()

>>> $user->microposts
=> Illuminate\Database\Eloquent\Collection {#759
     all: [],
   }

>>> $user->microposts()->create(['content' => 'micropost test'])

>>> $user->microposts()->get()
=> Illuminate\Database\Eloquent\Collection {#701
     all: [
       App\Micropost {#710
         id: 3,
         user_id: 1,
         content: "micropost test",
         created_at: "2016-12-08 18:48:39",
         updated_at: "2016-12-08 18:48:39",
       },
     ],
   }
9.2 Router
ログイン認証を必要とするルーティンググループ内に、 Microposts のルーティングを設定します。これで、ログイン認証しているユーザだけが MicropostsController にアクセスできます。

また、 今まで / は Router から Controller へ飛ばしていませんでしたが、ここからは少し複雑になるのでちゃんと MicropostsController を作成します。

routes/web.php auth ルーティンググループ内

Route::get('/', 'MicropostsController@index');

// 中略

Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});
それぞれのアクションを実装していきましょう。

9.3 MicropostsController@index
サインアップまたはログイン認証が行われていなければ Welcomeのviewを表示し,　
サインアップまたはログイン認証が正常ならば、一覧画面を表示させるためにuser.show を表示します。

 ここで user.show の画面を表示するのは 一旦この段階でのみ です。7.2節で ログイン状態によって分岐するように resources/views/welcome.blade.php を作成していますので、最終的にはbladeファイル内の分岐を活用する形で MicropostsController の index アクションを変更することになります。
MicropostsController
$ php artisan make:controller MicropostsController
app/Http/Controllers/MicropostsController.php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class MicropostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
            $data += $this->counts($user);
            return view('users.show', $data);
        }else {
            return view('welcome');
        }
    }
}
（$this->counts() については、この後のセクションで詳しく説明します。先に読みたい方は こちらを参照してください 。）

View
共通の View として、 microposts.blade.php を作成します。
後でログイン認証後の一覧で使用します。

resources/views/microposts/microposts.blade.php

<ul class="media-list">
@foreach ($microposts as $micropost)
    <?php $user = $micropost->user; ?>
    <li class="media">
        <div class="media-left">
            <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class="media-body">
            <div>
                {!! link_to_route('users.show', $user->name, ['id' => $user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
            </div>
            <div>
                <p>{!! nl2br(e($micropost->content)) !!}</p>
            </div>
        </div>
    </li>
@endforeach
</ul>
{!! $microposts->render() !!}
これで 9.6節の内容まで機能を実装すれば、ログイン後のトップページに自分の投稿した Microposts が表示されるようになります。 では次に Web 上のフォームで Micropost を投稿できるようにします。

9.4 MicropostsController@store
Controller
store アクションを実装します。 9.1節では tinker で投稿を作成しました。それと同様に、store アクションでは create メソッドを使って Micropost を保存しています。

app/Http/Controllers/MicropostsController.php store

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
9.5 MicropostsController@destroy
投稿の削除を実装します。

Controller
app/Http/Controllers/MicropostsController.php destroy

    public function destroy($id)
    {
        $micropost = \App\Micropost::find($id);

        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
        }

        return redirect()->back();
    }
View
削除ボタンを付け足します。

resources/views/microposts/microposts.blade.php

<ul class="media-list">
@foreach ($microposts as $micropost)
    <?php $user = $micropost->user; ?>
    <li class="media">
        <div class="media-left">
            <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class="media-body">
            <div>
                {!! link_to_route('users.show', $user->name, ['id' => $user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
            </div>
            <div>
                <p>{!! nl2br(e($micropost->content)) !!}</p>
            </div>
            <div>
                @if (Auth::id() == $micropost->user_id)
                    {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </li>
@endforeach
</ul>
{!! $microposts->render() !!}
9.6 UsersController@show
Controller
Micropost の数をカウント
Micropost の数のカウントを View で表示するときのために、 Controller.php に実装しておきます。これで、全てのコントローラで counts() が使用できます。全てのコントローラが Controller.php を継承しているからです。

app/Http/Controllers/Controller.php の class

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function counts($user) {
        $count_microposts = $user->microposts()->count();

        return [
            'count_microposts' => $count_microposts,
        ];
    }
}
users.show
上記の counts() を利用しています。

app/Http/Controllers/UsersController.php show

    public function show($id)
    {
        $user = User::find($id);
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'user' => $user,
            'microposts' => $microposts,
        ];

        $data += $this->counts($user);

        return view('users.show', $data);
    }
View
User の show でも Microposts を表示します。
また、投稿用の専用ページは作らず、ここに投稿フォームを設置します。
投稿フォームは、ログインされたuserのみに表示されるようにコーディングします。

resources/views/users/show.blade.php

@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
        </aside>
        <div class="col-xs-8">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', ['id' => $user->id]) }}">TimeLine <span class="badge">{{ $count_microposts }}</span></a></li>
                <li><a href="#">Followings</a></li>
                <li><a href="#">Followers</a></li>
            </ul>
            @if (Auth::id() == $user->id)
                  {!! Form::open(['route' => 'microposts.store']) !!}
                      <div class="form-group">
                          {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '2']) !!}
                          {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block']) !!}
                      </div>
                  {!! Form::close() !!}
            @endif
            @if (count($microposts) > 0)
                @include('microposts.microposts', ['microposts' => $microposts])
            @endif
        </div>
    </div>
@endsection
下記だけ少しややこしいので解説します。

                <li role="presentation" class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', ['id' => $user->id]) }}">TimeLine <span class="badge">{{ $count_microposts }}</span></a></li>
class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}" は、 /users/{id} という URL の場合には、 class="active" にするコードです。 Bootstrap のタブでは class="active" を付与することで、このタブが今開いているページだとわかりやすくなります。 Request::is はその判定のために使用しています。

参考: Request@is - Laravel API
<a href="{{ route('users.show', ['id' => $user->id]) }}"> で使用している route() はヘルパー関数と呼ばれるもので、今までは link_to_route を使用してきましたが、ここではこちらを使用しています。理由は、 <span class="badge">{{ $count_microposts }}</span> を含めたリンク名うまく表示されなかったからです。

参考: ヘルパー関数 - Laravel 5.5
9.7 Git
$ git status

$ git diff

$ git add .

$ git commit -m 'post'
課題：ログイン認証と一対多
Twitterクローンを参考にして、タスク管理アプリにログイン認証機能をつけて、ログインしているユーザが自身の作成したタスクのみにアクセスできるようにしてください。



ログイン認証機能をつけてください。
マイグレーションで外部キー制約を追加される際に既存データがある場合、外部キーのカラムにNULLが入るため外部キーが作成できないエラーが発生します。
マイグレーション前にmysqlコマンドかtinkerでデータを事前削除しましょう。
未ログイン状態ではタスクの作成、編集、削除、表示ができないようにしてください。
ログインしたユーザーがタスクを投稿できるようにしてください
ログインユーザが、自分自身のタスクのみを操作可能 (表示、編集、削除) にして、他のユーザーからのアクセスは全てトップページにリダイレクトしてください。
GitHub に完成した最新のソースコードをプッシュしてください。
Heroku にデプロイしてください。
Heroku デプロイ時のエラー対処
エラーとなる理由について説明します。DB の tasks テーブルに user_id を追加するとき、マイグレーションファイルで $table->integer('user_id')->unsigned()->index(); と $table->foreign('user_id')->references('id')->on('users'); のように指定したと思います。このようにカラムに index や foregin key を設定した場合、そのカラムは null となることが許されなくなります。ここで問題なのが、このマイグレーション以前に作成されたタスクが1つでも存在すると、その既存のタスクの user_id カラムは null となってしまい、DB でマイグレーションエラーが発生し、結果的にデプロイは失敗します。

対処としては、以前のタスクを全て削除することです。

$ heroku run php artisan tinker
で heroku 上の tinker を起動し、全ての既存タスク削除しましょう。

10. フォロー機能
10.1 Model
多対多の関係
一対多だけでなく、多対多の関係もあります。

今回実装する機能であるフォローユーザとフォロワーユーザの関係は、多対多の関係です。また、お気に入り機能も、多対多の関係です。

多対多の関係は、一対多の関係を拡張した関係です。一対多との違いから理解しましょう。一対多の関係だった User と Micropost では、 User が複数の Micropost を持ち、 Micropost は必ず1つの User に所属しました。多対多の関係であるお気に入り機能では、 User A がとある Micropost X をお気に入りに追加したとして、その Micropost は必ず1つの User A に所属するかと言えば、そうではありません。 Micropost X は User A だけでなく、 User B や User C にもお気に入りに追加されても良いのです。 User A がお気に入りに追加している Micropost は複数あり、 Micropost X をお気に入りに追加している User も複数いるという関係が多対多の関係です。



この違いは必ず理解してください。一対多と同様に多対多のリソースを扱う方法もしっかりと学んでおく必要があります。多対多の関係が稀な関係だとは思わないでください。一対多と同様に頻繁に出てくる関係になります。

多対多では中間テーブルが必要
一対多では、 microposts テーブルを作成するときに user_id を付与しました。microposts テーブルに user_id を設置することで、 Micropost が所属する User を特定できたのです。そして、 belongsTo と hasMany のメソッドによって両者をモデルファイルで接続することができようになり、 $user->microposts や $micropost->user が使用可能になったわけです。

多対多では、片方のテーブルに xxxx_id のようなカラムを設置するだけでは実現できません。実現できなくもないですが、カラムの中身が配列になってしまいます。データベースの1つの値が配列になってしまうのは、とても扱いにくく好ましくありません。

そこで、多対多の場合には、中間テーブルを設置するのが最も有効な方法です。中間テーブルとは、 users や microposts のような、メインとなるリソースではなく、その関係を接続するためだけのテーブルを言います。例えば、 User が特定の Micropost をお気に入りする場合、 users テーブルの id と microposts テーブルの id を接続する favorites テーブルを作成します。 favorites テーブルには user_id と micropost_id を設置します。この favorites テーブルのレコードに user_id が 1 で、 micropost_id が 10 のものがあったとすると、 id が 1 の User が id が 10 の Micropost をお気に入りに追加しているということを意味します。

ここで実装するフォローの関係も同様です。ただし、 User と Micropost ではなく、 User と User なので、同じテーブルに対しての中間テーブルになります。と言っても、 考え方は全く同じで、 ただ Micropost が User に代わったに過ぎません。

マイグレーション
マイグレーションファイルの作成
では、 User と User のフォロー関係のレコードを保存する中間テーブル user_follow を作成します。

$ php artisan make:migration create_user_follow_table --create=user_follow
database/migrations/年月日時_create_user_follow_table.php の up() と down()

    public function up()
    {
        Schema::create('user_follow', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('follow_id')->unsigned()->index();
            $table->timestamps();

            // 外部キー設定
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follow_id')->references('id')->on('users')->onDelete('cascade');

            // user_idとfollow_idの組み合わせの重複を許さない
            $table->unique(['user_id', 'follow_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
中間テーブルの役割として、しっかり user_id と follow_id を入れています。 follow_id と書いていますが、これも中身は user_id のことです。しかし、 user_id というカラム名が被ってしまうので、 follow_id にしています。これで User と User のフォロー関係を保存することができます。

コメントでも説明していますが、 user_id と follow_id の組み合わせの重複を許さないようにしています。これは何度もフォローできないようにテーブルの制約として入れています。

onDelete は参照先のデータが削除されたときにこのテーブルの行をどのように扱うかを指定します。 オプションとして以下の値をセットして、削除後の挙動を制御できます。　

set null: NULL に設定 (ID を NULL に変更します)

no action: なにもしない (存在しない ID が残ります)

cascade: 一緒に消す (このテーブルのデータも一緒に消えます)

restrict: 禁止する (参照先のデータが消せなくなります)
今回は、onDelete('cascade') で、ユーザーテーブルのユーザーデータが削除されたら、それにひもづくフォローテーブルのフォロー、フォロワーのレコードも削除されるようにしましょう。

            // user_idとfollow_idの組み合わせの重複を許さない
            $table->unique(['user_id', 'follow_id']);
マイグレーションの実行
$ php artisan migrate
belongsToMany()
中間テーブルのためのモデルファイルは不要です。

その代わり、 User のモデルファイルに多対多の関係を記述します。 belongsToMany メソッドを使用します。フォロー関係の場合、多対多の関係がどちらも User に対するものなので、どちらも User のモデルファイルに記述します。

app/User.php の追加分

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
これで、一対多のときと同様に、 $user->followings で $user がフォローしている User 達を取得することができます。 $user->followers も同様です。

followings が User がフォローしている User 達で、 followers が User をフォローしている User 達です。

belongsToMany() では、第一引数に得られる Model クラス (User::class) を指定し、第二引数に中間テーブル (user_follow) を指定し、第三引数に中間テーブルに保存されている自分の id を示すカラム名 (user_id) を指定し、第四引数に中間テーブルに保存されている関係先の id を示すカラム名 (follow_id) を指定します。

また、 withTimestamps() は中間テーブルにも created_at と updated_at を保存するためのメソッドでタイムスタンプを管理することができるようになります。

参考: 多対多 - Laravel 5.5
follow(), unfollow()
$user->follow($user_id) や、$user->unfollow($user_id) とすれば、フォロー／アンフォローできるように follow() とunfollow() メソッドを User モデルで定義しておきましょう。

app/User.php の追加分

public function follow($userId)
{
    // 既にフォローしているかの確認
    $exist = $this->is_following($userId);
    // 自分自身ではないかの確認
    $its_me = $this->id == $userId;

    if ($exist || $its_me) {
        // 既にフォローしていれば何もしない
        return false;
    } else {
        // 未フォローであればフォローする
        $this->followings()->attach($userId);
        return true;
    }
}

public function unfollow($userId)
{
    // 既にフォローしているかの確認
    $exist = $this->is_following($userId);
    // 自分自身ではないかの確認
    $its_me = $this->id == $userId;

    if ($exist && !$its_me) {
        // 既にフォローしていればフォローを外す
        $this->followings()->detach($userId);
        return true;
    } else {
        // 未フォローであれば何もしない
        return false;
    }
}

public function is_following($userId) {
    return $this->followings()->where('follow_id', $userId)->exists();
}
フォロー／アンフォローするときには、2つ注意することがあります。それは、

既にフォローしているか
自分自身ではないか
です。

これらをしっかり判定してからフォロー／アンフォローを実行しましょう。

フォロー／アンフォローとは、中間テーブルのレコードを保存／削除することです。そのために attach() と detach() というメソッドが用意されているので、それを使用します。

一応成功すれば、 return true 、失敗すれば return false を返しています。今回実際には使用していませんが、何か成功失敗を判定したい場合には利用できます。

参考: attach/detach - Laravel 5.5
tinker でフォロー／アンフォロー
前提として User を2人以上作成していきましょう。1人がもう1人をフォロー／アンフォローするためです。

あらかじめデータベースに２人以上のユーザーを登録しておいてください。登録がないとfindメソッド実行してもnullが返ってきてしまいます。

>>> use App\User

// 今回はユーザーIDが1と2のユーザーを使ってテストしますが、任意のID番号でも問題ありません。
>>> $user1 = User::find(1)

>>> $user2 = User::find(2)

>>> $user1->follow($user2->id)

>>> $user1->followings()->get()
>>> $user1->unfollow($user2->id)

>>> $user1->followings()->get()
これで User がフォロー／アンフォローを自由にできるようになりました。

中間テーブルがどうなっているのか気になれば、 MySQL クライアントで直接レコードを確認してみるのも良いでしょう。

10.2 Router
routes/web.php の auth グループ抜粋

Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
    });

    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});
Route::group として ['prefix' => 'users/{id}'] を追加しています。このグループ内のルーティングでは、 URL の最初に /users/{id}/ が付与されます。
つまり上記の4つは

POST /users/{id}/follow
DELETE /users/{id}/unfollow
GET /users/{id}/followings
GET /users/{id}/followers
となります。

上記だけではイメージしづらいかと思いますので、以下にデモサイトでの参考URLを記載します。

例) ユーザーID125のユーザーの場合

// 125番目のユーザーをフォローする(こちらのURLはクリックしただけではフォローできません。あくまで参考です。)

http://laravel-microposts.herokuapp.com/users/125/follow [POST形式]

// 125番目のユーザーをアンフォローする(こちらのURLはクリックしただけではアンフォローできません。あくまで参考です。)

http://laravel-microposts.herokuapp.com/users/125/unfollow [DELETE形式]

// 125番目のユーザーがフォローしているユーザー一覧を表示する

http://laravel-microposts.herokuapp.com/users/125/followings [GET形式]

// 125番目のユーザーをフォローしているユーザー一覧を表示する

http://laravel-microposts.herokuapp.com/users/125/followers [GET形式]

当然、上記の POST と DELETE はフォロー／アンフォローを HTTP で操作可能にするルーティングです。

UserFollowController は後の新規作成するカリキュラムで説明致します。

そして、 GET の2つはフォローしている人とフォローされている人の User 一覧を表示することになります。

10.3 UserFollowController@store, destroy
Controller
フォロー機能のためのモデルやルーティングが作成できたので、次はコントローラを作成しましょう。UserController.phpやMicropostsController.phpファイルがすでにありますが、 今回はユーザーと投稿の両方に関連するということで新しくUserFollowController.phpファイルを作成して、フォローするためのstoreメソッドとアンフォローするためのdestroyメソッドを作成します。

storeメソッドの中でUser.phpの中で定義したfollowメソッドを使ってユーザーをフォローできるようにします。
destroyメソッドの中でUser.phpの中で定義したunfollowメソッドを使ってユーザーをアンフォローできるようにします。
$ php artisan make:controller UserFollowController
app/Http/Controllers/UserFollowController.php の store と destroy

class UserFollowController extends Controller
{
    public function store(Request $request, $id)
    {
        \Auth::user()->follow($id);
        return redirect()->back();
    }

    public function destroy($id)
    {
        \Auth::user()->unfollow($id);
        return redirect()->back();
    }
}
フォロー／フォロワー数のカウント
フォロー／フォロワー数のカウントを View で表示するとき、全ての Controller が使用できるように Controller.php に追加します。

app/Http/Controllers/Controller.php の class

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function counts($user) {
        $count_microposts = $user->microposts()->count();
        $count_followings = $user->followings()->count();
        $count_followers = $user->followers()->count();

        return [
            'count_microposts' => $count_microposts,
            'count_followings' => $count_followings,
            'count_followers' => $count_followers,
        ];
    }
}
View
フォロー／アンフォローボタン
共通のフォロー／アンフォローボタンを用意しておきましょう。

resources/views/user_follow/follow_button.blade.php

@if (Auth::id() != $user->id)
    @if (Auth::user()->is_following($user->id))
        {!! Form::open(['route' => ['user.unfollow', $user->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfollow', ['class' => "btn btn-danger btn-block"]) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['user.follow', $user->id]]) !!}
            {!! Form::submit('Follow', ['class' => "btn btn-primary btn-block"]) !!}
        {!! Form::close() !!}
    @endif
@endif
ユーザ一覧ページなどから、ユーザ個別ページへ移動し、フォローボタンをクリックすることが可能になります。既にフォローしている場合にはアンフォローボタンになります。また、自分自身の場合には表示されません。

フォロー／アンフォローボタンの設置
users.show にボタンを設置します。

resources/views/users/show.blade.php 抜粋

        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
            @include('user_follow.follow_button', ['user' => $user])
        </aside>
下記を追加しています。

            @include('user_follow.follow_button', ['user' => $user])
10.4 UsersController@followings, followers
Controller
こちらは UsersController へ記述します。

app/Http/Controllers/UsersController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Micropost; // 追加

class UsersController extends Controller
{

    〜中略〜

    public function followings($id)
    {
        $user = User::find($id);
        $followings = $user->followings()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followings,
        ];

        $data += $this->counts($user);

        return view('users.followings', $data);
    }

    public function followers($id)
    {
        $user = User::find($id);
        $followers = $user->followers()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followers,
        ];

        $data += $this->counts($user);

        return view('users.followers', $data);
    }
View
followings
resources/views/users/followings.blade.php

@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
            @include('user_follow.follow_button', ['user' => $user])
        </aside>
        <div class="col-xs-8">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', ['id' => $user->id]) }}">TimeLine <span class="badge">{{ $count_microposts }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followings') ? 'active' : '' }}"><a href="{{ route('users.followings', ['id' => $user->id]) }}">Followings <span class="badge">{{ $count_followings }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followers') ? 'active' : '' }}"><a href="{{ route('users.followers', ['id' => $user->id]) }}">Followers <span class="badge">{{ $count_followers }}</span></a></li>
            </ul>
            @include('users.users', ['users' => $users])
        </div>
    </div>
@endsection
followers
resources/views/users/followers.blade.php

@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
            @include('user_follow.follow_button', ['user' => $user])
        </aside>
        <div class="col-xs-8">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', ['id' => $user->id]) }}">TimeLine <span class="badge">{{ $count_microposts }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followings') ? 'active' : '' }}"><a href="{{ route('users.followings', ['id' => $user->id]) }}">Followings <span class="badge">{{ $count_followings }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followers') ? 'active' : '' }}"><a href="{{ route('users.followers', ['id' => $user->id]) }}">Followers <span class="badge">{{ $count_followers }}</span></a></li>
            </ul>
            @include('users.users', ['users' => $users])
        </div>
    </div>
@endsection
User@show
resources/views/users/show.blade.php

@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
            @include('user_follow.follow_button', ['user' => $user])
        </aside>
        <div class="col-xs-8">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', ['id' => $user->id]) }}">TimeLine <span class="badge">{{ $count_microposts }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followings') ? 'active' : '' }}"><a href="{{ route('users.followings', ['id' => $user->id]) }}">Followings <span class="badge">{{ $count_followings }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followers') ? 'active' : '' }}"><a href="{{ route('users.followers', ['id' => $user->id]) }}">Followers <span class="badge">{{ $count_followers }}</span></a></li>
            </ul>
            @if (Auth::id() == $user->id)
                  {!! Form::open(['route' => 'microposts.store']) !!}
                      <div class="form-group">
                          {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '2']) !!}
                          {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block']) !!}
                      </div>
                  {!! Form::close() !!}
            @endif
            @if (count($microposts) > 0)
                @include('microposts.microposts', ['microposts' => $microposts])
            @endif
        </div>
    </div>
@endsection
10.5 Git
$ git status

$ git diff

$ git add .

$ git commit -m 'follow, unfollow'
11. タイムラインの表示
Twitter のトップのタイムラインページでは、自分がフォローしたユーザの投稿も表示されます。

最後に、 welcome にフォローしたユーザのマイクロポストも表示させましょう。

11.1 User モデルに機能追加
タイムライン用のマイクロポストを取得するためのメソッドを実装します。

app/User.php

     public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
$this->followings()-> pluck('users.id')->toArray(); では User がフォローしている User の id の配列を取得しています。 pluck() は与えられた引数のテーブルのカラム名だけを抜き出します。そして更に toArray() でただの配列に変換しています。

更に $follow_user_ids[] = $this->id; で自分の id も追加しています。自分自身のマイクロポストも表示させるためです。

最後に return Micropost::whereIn('user_id', $follow_user_ids); では、 microposts テーブルの user_id カラムで $follow_user_ids の中の id を含む場合に、全て取得して return します。

11.2 MicropostsController@index
Controller
（indexアクションの部分のみ抜粋）

    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }
        return view('welcome', $data);
    }
11.3 welcome.blade.php
ログイン後の画面で microposts を表示させる記述を welcome に追加します。

resources/views/welcome.blade.php

@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="row">
            <aside class="col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $user->name }}</h3>
                    </div>
                    <div class="panel-body">
                        <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                    </div>
                </div>
            </aside>
            <div class="col-xs-8">
                @if (count($microposts) > 0)
                    @include('microposts.microposts', ['microposts' => $microposts])
                @endif
            </div>
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Microposts</h1>
                {!! link_to_route('signup.get', 'Sign up now!', null, ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection
11.4 Git
$ git status

$ git diff

$ git add .

$ git commit -m 'timeline'
12. Heroku
これから、作成した Microposts を Heroku に、デプロイしていきます。

12.1 Microposts のプロジェクトフォルダへ移動
まずは、Microposts のプロジェクトフォルダへ移動します。Microposts の Git を利用するためです。

$ cd ~/environment/microposts/
12.2 Heroku へログイン
まずは、ターミナル上で、Heroku にログインします。

$ heroku login

Enter your Heroku credentials.
Email: Herokuに登録したメールアドレスを入力
Password: Herokuに登録したパスワードを入力
12.3 Heroku アプリを作成
Microposts の Heroku アプリを作成します。

$ heroku create Herokuアプリ名
Herokuアプリ名は、他の人と重複できません。例えば、今筆者が heroku create microposts でHerokuアプリをを作ってしまえば、もう microposts という名前は使えません。

そのため、皆さんも独自の名前を付けてください（おさらいですが、Herokuアプリ名は、小文字、数字、-(ダッシュ)のみを含むことができ、先頭が文字である必要があります。大文字やアンダーバーを含めてしまうとエラーになりますのでご注意ください）。例えば、 Herokuアカウント名-microposts などにしてください。

リモートリポジトリ heroku の確認
Herokuアプリを作成したら、 git にリモートリポジトリとして heroku が作成されています。リモートリポジトリを確認してみましょう。

$ git remote -v
12.4 Heroku 用設定ファイルを新規作成
Heroku アプリ用に Procfile というファイル名前でファイルを作成し、下記のコードで保存してください。これは、どのサーバを使うかという指定になります。 apache2 サーバを利用します。

Procfile

web: vendor/bin/heroku-php-apache2 public/
Git のコミットを最新に
Procfile を作成したら、Git のコミットを最新にしておきます。

$ git add .

$ git commit -m 'Procfile'
12.5 デプロイ
では、一度デプロイしてみましょう。

登録されたリモートリポジトリ heroku に対して、 git push を行うと、そのままデプロイされます。何かファイルを更新したとしても、 git commit してから、git push heroku msater するだけで、とても簡単にデプロイすることが可能です。

$ git push heroku master
デプロイ中は少し待つ必要があります。

12.6 Heroku アプリを開く
では、Heroku アプリを開いてみましょう。

下記のように自分で設定した Herokuアプリ名 の URL にアクセスしてみてください。

https://Herokuアプリ名.herokuapp.com/
ただし、 エラーになる はずです。まだ環境変数などの設定を行っていないからです。

12.7 Herok アプリの環境変数の設定
いくつか設定しなければならない環境変数があります。Cloud9 上では .env が環境変数の役割を担っていましたが、 .env は .gitignore によって無視ファイルとして扱われているので、Git のコミットには含まれていません。

また、環境変数は、その名の通り、環境の変数であるため、環境が異なる毎に設定する必要があります。

APP_KEY
APP_KEY は Laravel アプリケーションのセキュリティを強化するために設定されるものです。これを最初に設定しておく必要があります。ローカル(Cloud9)でも .env 上に APP_KEY が設定されています。

$ heroku config:set APP_KEY=$(php artisan --no-ansi key:generate --show)
環境変数の確認方法
Heroku アプリ上の環境変数を確認する方法を知っておきましょう。適宜、確認してみてください。

今しがた設定した APP_KEY が設定されているはずです。

$ heroku config

APP_KEY: RqucDkP83PGi8dHjr7...
12.8 データベースの設定
データベースの作成
Heroku の標準データベースは、MySQL ではなく、PostgreSQL です。基本的なデータベースとしての違いはありません。どちらも、今まで学んだ SQL が同じく動作します。

Heroku では、アドオンを追加する形で、PostgreSQL のデータベースを作成します。アドオンにも、無料プランと有料プランがあり、 hobby-dev は PostgreSQL の唯一の無料プランです。 hobby-dev はお試し用のデータベースで、10000レコードまで、同時接続は20までといった制限があります。

Heroku Postgres
$ heroku addons:create heroku-postgresql:hobby-dev
データベースを作成したら、環境変数 DATABASE_URL が設定されています。

DATABASE_URL: postgres://...
また、作成したデータベースは、Heroku の Web ページで確認することもできます。

Datastores
環境変数を設定
.env に設定したように、他にもデータベース用に環境変数を設定しなければいけません。

DATABASE_URL は、下記のような形になっています。

postgres://ユーザ名:パスワード@ホスト名:5432/データベース名
これがそのまま環境変数となるので、下記の環境変数となるように設定してください。

DB_CONNECTION=pgsql
DB_USERNAME=ユーザ名
DB_PASSWORD=パスワード
DB_HOST=ホスト名
DB_DATABASE=データベース名
Heroku アプリの環境変数の設定は、下記のコマンド例のようになります。これで1つずつ上記のように設定してください。

$ heroku config:set DB_CONNECTION=pgsql
ちなみに pgsql になるのは、 config/database.php に 'default' => env('DB_CONNECTION', 'mysql'), とあり、その下にある 'connections' => [...] の中で 'pgsql' => [...] と定義されているからで、その 'pgsql' の設定を指定してます。

12.9 マイグレーション
heroku run コマンド で Heroku アプリ上でコマンドを実行することができます。先ほどまでの設定ができていれば、エラーなくマイグレーションが成功するでしょう。

$ heroku run php artisan migrate

Running php artisan migrate on ⬢ laravel-microposts... up, run.1835 (Free)
**************************************
*     Application In Production!     *
**************************************

 Do you really wish to run this command? (yes/no) [no]:
 > yes

Migration table created successfully.
12.10 動作確認
環境変数を設定し、マイグレーションを行ったので、ようやく正常に動くはずです。

もう一度 heroku open で Heroku アプリを開いてみてください。

エラーが出ずに表示されれば、デプロイ成功です。

13. まとめ
今回作成した Microposts の大きな特徴と言えば、ログイン認証と、リレーション（一対多、多対多）です。

ログイン認証は、既に用意されていた RegisterController を使えば良かったですね。復習を兼ねて、下記の公式ドキュメントを一通り読んでおくことをお勧めします。このレッスンで学んでいないこともあるので、学んだところだけでも構いません。

認証
また、リレーションは Model と Model の関係のことです。一対多か、多対多かによって作成方法が異なりました。特に、多対多のリレーションを作るには、中間テーブルと呼ばれるテーブルが必要でした。中間テーブルは、 Model と Model を結ぶためのテーブルです。余談ですが、 Laravel だけでなく、他の Rails や CakePHP と言った Web アプリケーションフレームワークでも考え方は同じで、リレーションのために中間テーブルを必要とします。

一対多、多対多のリレーションの作り方を一度わかってしまえば、 Web アプリケーションの開発はぐっと簡単になり、何でも作れるようになるでしょう。こちらも復習を兼ねて、下記の公式をドキュメントを一通り読んでおくことをお勧めします。このレッスンで学んでいないこともあるので、学んだところだけでも構いません。

リレーション
ここまで学べば Laravel を使って Web アプリケーションを作成する方法がほぼわかったと思います。これからはオリジナルサービスのレッスンに進むも良し、 Web API の利用方法を知りたい方はモノリストのレッスンに進むも良しです。

今回筆者がこの Laravel に関するレッスンを執筆するために最も参考にしたのは当然のことながら、 Laravel の公式ドキュメントです。プログラミングの資料の多くが英語で説明されるなか、公式ドキュメントが日本語に翻訳されていることは幸運なことであり、翻訳者には感謝しておきましょう。

公式ドキュメント
更に Laravel を理解したい方や、少し疑問が残った方は是非上記の公式ドキュメントを読破してください。公式ドキュメントを読破した暁には、 Laravel の全容をよりよく把握できることでしょう。

また、 Laravel API 検索サービスもとても役に立ちます。公式ドキュメントだけでは理解に至らないケースもあるので、本質的に理解するには Laravel 本体のソースコードを読んでしまうのが一番です。

Laravel API
Laravel - GitHub
課題：Micropost のお気に入り機能
Micropost のお気に入り機能を追加してください。

以下に、仕様を述べます。

特定の Micropost をログインユーザがお気に入りリストに追加できるようにボタンを設置してください。
そのボタンは、既にお気に入りに追加したものに対しては、外せるボタンとなるように工夫してください。
ログインユーザがお気に入りに追加した Micropost を一覧表示するページを、ユーザー詳細ページのページタブに追記してください。
トップページ もしくは ナビバー のどちらか一方からで良いので、前述のお気に入り一覧ページにアクセスできるようにリンクを作成してください。
下記は参考のレイアウトです。赤枠部分を参考に作成ください。



GitHub に kadai-microposts でリモートリポジトリを作成して、プッシュしてください。
Herokuへデプロイしてください。
このレッスンはここまでです。
レッスンが完了したら喜びの気持ちをシェアしましょう！
