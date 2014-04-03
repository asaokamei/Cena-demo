Cena-demo
=========

Cenaのデモ用プロジェクト。
簡単なブログサイトを構築する。

ORMとしてDoctrine2をベースに利用。
フレームワークに依存しない、古き良きPHPコード。

注意：
あくまでデモ用のサンプルサイトとして開発しており、
CSRFなどのセキュリティ対策を行っていませんので、
絶対に一般に公開しないでください。

#### Cenaとは

Composite Entity Notation and Augmentationの略。
エンティティオブジェクトの状態（ライフサイクル、プロパティ、そしてリレーション）
をテキスト記述する技術の総称。


インストール
----------

#### Cena-demoの入手

githubから最新のコードをダウンロードしてから、composerでインストール。

```
git clone https://github.com/asaokamei/Cena-demo
cd Cena-demo
php composer.phar install
```

#### セットアップ

1.  config/dbParam.php を修正して、データベース設定を反映する。
2.  config/setup-db.php を走らせて、デモ用のDBテーブルを作成する。
3.  config/sample-db.php を走らせると、サンプルデータを登録する。
4.  デモは ```public_legacy```。
    なので、簡単にデモを使うには、下記を走らせてみる。
    ```
    ln -s /path/to/Cena-demo/public_legacy /doc/root/cena
    ```
5.  ```http://localhost/cena/```にアクセスする。

