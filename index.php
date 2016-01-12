<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>演習1</title>
	<link rel="author" href="https://github.com/kato-mono">
</head>
<body>
	<article>

		<h1>HyperText Markup Language</h1>

		<p>
			<strong>[概要]</strong>
			<br>
			HyperText Markup Language（ハイパーテキスト マークアップ ランゲージ）、
			略記・略称HTML（エイチティーエムエル）とは、
			<br>
			ウェブ上の文書を記述するためのマークアップ言語である
			(チューリング完全ではないためプログラミング言語ではない)。
			<br>
			<br>
			- 中略 -
		</p>

		<p>
			<strong>[特徴]</strong>
			<br>
			HTMLの特徴はハイパーテキストを利用した、相互間文書参照のフレームワークである。
			マークアップはプレーンテキストの文書を
			<br>
			要素で括って意味付けすることで行い、文書の特定要素にURIを用いた他文書へのリンクを記載しておけば
			ユーザエージェントは
			<br>
			それを解釈して指定された他文書を表示させることが可能となる。
			<br>
			<br>
			- 中略 -
		</p>


		<p>
			<strong>[構造]</strong>
			<br>
			このHTML文書は次のような構造を示している。
		</p>
		<ul>
			<li>html 要素（ルート要素。また、言語コード ja の言語が使われていることの明示）</li>
			<ul>
				<li>head 要素（この文書のヘッダ情報の明示）</li>
				<li>meta 要素（文書のメタ情報）</li>
				<li>link 要素（他のリソースとの関連を明示。この場合、作者の明示）</li>
				<li>title 要素（この文書のタイトルの明示、この部分は en の言語が使われていることの明示）</li>
				<li>body 要素（この文書の内容の明示）</li>
				<ul>
					<li>article 要素（記事を明示）</li>
					<ul>
						<li>h1 要素（第一レベルの見出しを明示、この部分は en の言語が使われていることの明示）</li>
						<li>p 要素（段落の明示）</li>
						<ul>
							<li>a 要素（他のリソースへのアンカーであることの明示）</li>
							<li>strong 要素（強い強調であることの明示）</li>
						</ul>
					</ul>
				</ul>
			</ul>
		</ul>
	</article>
</body>
</html>
