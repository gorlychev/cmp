# Just a test
Source code is written in command folder

### Installation
DB:
CREATE TABLE `videos` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(200) DEFAULT NULL,
 `url` varchar(300) DEFAULT NULL,
 `status` int(11) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`),
 KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1

CREATE TABLE `tags` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `tag` varchar(50) DEFAULT NULL,
 `videoid` int(11) DEFAULT NULL,
 `status` int(11) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1

CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url` text,
  `format` enum('JSON','YAML','FTP','XML') DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `sources` (`id`, `name`, `url`, `format`, `status`) VALUES
(1, 'flub', 'http://dev.titles.ws/jobs/test1/feed-exports/flub.yaml', 'YAML', 1),
(2, 'glorf', 'http://dev.titles.ws/jobs/test1/feed-exports/glorf.json', 'JSON', 1),
(3, 'no works', 'http://dev.titles.ws/jobs/test1/feed-exports/glorf-noexists.json', 'JSON', 1);


ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


```sh
$ git clone ..... cmp
$ composer install
```
Do not forget to change DB access data in "config" folder


### For console run
```sh
$ php yii hello/get-source
```

### For unitTest
```sh
$ vendor/bin/codecept run
```
