# Maestro

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
composer global require snelling/maestro
```

This is still a work in progress, and probably will need to be installed by hand until I have officially tagged this repository.

## Usage

Go to any directory and init a new maestro project with the command `maestro init`
``` bash
➜  maestro init
Config file & scripts directory created!
```

This command will create a new `maestro/` folder, and put a few things in there for you.
``` bash
➜  tree maestro
maestro
├── configuration.json
└── scripts
    ├── ls
    └── pwd

1 directory, 3 files
```

The `maestro/configuration.json` file is used to define targets. A target is a local or remote machine to run a command against.
```json
{
  "targets": {
    "local": "127.0.0.1",
    "web": "user@192.168.1.1"
  }
}
```

You can list the available targets at any time by running `maestro targets`
``` bash
➜  maestro targets
 ------- ------ -------------
  Name    User   IP Address
 ------- ------ -------------
  local          127.0.0.1
  web     user   192.168.1.1
 ------- ------ -------------
```

The `maestro/scripts/` folder is where you will put any bash script that you would like to run against a target. By default we include two commands out of the box, `ls` and `pwd`. You can list all available scripts to run by running the command `maestro scripts`
``` bash
➜  maestro scripts
 ------ ------------------------------------
  Name   Description
 ------ ------------------------------------
  ls     Runs ls -al command
  pwd    Gets the current working directory
 ------ ------------------------------------
```

The name of the script is just the script filename. The description of the script is a bash comment inside the bash script. You need to add `# @description <your description>` to make a description show up in the `maestro scripts` command.
``` bash
➜  cat maestro/scripts/ls
#!/usr/bin/bash
# @description Runs ls -al command
set -e
ls -al
```

Let's make a new script that gets a machine's hostname.
``` bash
➜  echo '#!/usr/bin/bash
# @description Gets the hostname of the machine
set -e
hostname' >maestro/scripts/hostname
```

Then, we can verify it comes up as valid script by re-running the `maestro scripts` command.
``` bash
➜  maestro scripts
 ---------- ------------------------------------
  Name       Description
 ---------- ------------------------------------
  hostname   Gets the hostname of the machine
  ls         Runs ls -al command
  pwd        Gets the current working directory
 ---------- ------------------------------------
```

Finally, you can run script against a target by running `maestro run <script name> <target name>`
``` bash
➜  maestro run hostname local
Running script hostname on 127.0.0.1
[127.0.0.1]: localhost
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email sam@onroi.com instead of using the issue tracker.

## Credits

- [Sam Snelling][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/snelling/maestro.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/snelling/maestro/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/snelling/maestro.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/snelling/maestro.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/snelling/maestro.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/snelling/maestro
[link-travis]: https://travis-ci.org/snelling/maestro
[link-scrutinizer]: https://scrutinizer-ci.com/g/snelling/maestro/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/snelling/maestro
[link-downloads]: https://packagist.org/packages/snelling/maestro
[link-author]: https://github.com/snellingio
[link-contributors]: ../../contributors
