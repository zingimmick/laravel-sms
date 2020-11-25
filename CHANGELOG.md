# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

PRs and issues are linked, so you can find more about it. Thanks to [ChangelogLinker](https://github.com/Symplify/ChangelogLinker).

<!-- changelog-linker -->

## [3.4.0] - 2020-11-25

### Changed

- [#113] Support for Smsbao and Tiniyo
- [#112] Bump codecov/codecov-action from v1.0.14 to v1.0.15
- [#111] Update codecov/codecov-action action to v1.0.15
- [#110] Provisional support for PHP 8.0
- [#109] Setup problem matchers for PHPUnit
- [#108] Update actions/checkout action to v2
- [#107] Change and if to early return
- [#106] Bump codecov/codecov-action from v1.0.13 to v1.0.14
- [#105] Bump paambaati/codeclimate-action from v2.7.3 to v2.7.4
- [#104] Bump paambaati/codeclimate-action from v2.7.2 to v2.7.3
- [#103] Bump paambaati/codeclimate-action from v2.7.1 to v2.7.2
- [#102] Bump paambaati/codeclimate-action from v2.6.0 to v2.7.1
- [#101] Gateways config schema test

### Removed

- [#100] Remove Composer 2 in test

## [3.3.1] - 2020-09-13

### Changed

- [#97] Support for Laravel 8
- [#98] YAML to PHP

### Removed

- [#99] Drop Lumen tests

## [3.3.0] - 2020-08-23

### Changed

- [#96] Safely instantiate class SmsMessage
- [#95] Safely instantiate class CouldNotSendNotification
- [#94] Improve coding standard config
- [#93] Improve coding standard config
- [#91] Bump codecov/codecov-action from v1.0.12 to v1.0.13

### Fixed

- [#90] Fix wrong name of gateway
- [#89] Fix return type of DummyGateway
- [#88] Fix driver expects gateway but object provided
- [#87] Fix return type of NullGateway
- [#85] Fix return type of MeilianGateway
- [#84] Fix default arguments for routeNotificationForSms
- [#83] Fix the inherited return type is different to the implemented return type

## [3.2.0] - 2020-08-05

### Added

- [#81] Add verification code manager

### Changed

- [#82] Use double pipe for OR condition
- [#79] Configure Kodiak
- [#78] Ordered imports
- [#77] Configure Renovate

## [3.1.0]

- [#70] Added tentative support for composer 2
- [#68] Add connection alias
- [#76] Bump codecov/codecov-action from v1.0.11 to v1.0.12
- [#74] switch config from YAML to PHP
- [#72] Bump codecov/codecov-action from v1.0.10 to v1.0.11
- [#67] Revert "Psalm integration"
- [#66] Psalm integration
- [#73] Fix security alerts
- [#69] Fix style
- [#75] Fix security alerts

## [3.0.1] - 2020-07-07

- [#61] Bump actions/cache from v1 to v2
- [#60] Bump codecov/codecov-action from v1 to v1.0.7
- [#65] Update .gitattributes
- [#62] Bump codecov/codecov-action from v1.0.7 to v1.0.10
- [#63] Bump zing/coding-standard from ^1.0 to ^1.2.3
- [#51] Imporve Docs
- [#58] Fix [#55]
- [#57] Fix [#54]
- [#52] Fix style
- [#59] Remove useless extensions

## [3.0.0] - 2020-06-20

- [#38] Add license scan report and status
- [#37] Documentation improvement

## [3.0.0-alpha.5]

- [#34] Improve github action cache
- [#33] Revert pest integration

## [3.0.0-alpha.2]

- [#24] Test with pest.
- [#27] Remove PhoneNumber.

## [2.3.0] - 2020-06-20

- [#43] Add environment generator
- [#48] Complexity decrease
- [#46] Environment tests improvement
- [#45] Upgrade Lumen framework minimum version
- [#44] Apply fixes from StyleCI
- [#50] Fix style
- [#47] Fix type issues

## [2.2.0] - 2020-06-13

- [#41] Add overtrue/easy-sms integration test
- [#40] Add qiniu channel config
- [#42] Minimum require composer/composer
- [#39] Default config improvement

## [2.1.1] - 2020-06-09

- [#32] Downgrade dependencies

## [2.1.0] - 2020-06-01

- [#30] Support Lumen

## [2.0.1] - 2020-05-21

- [#25] Marking class PhoneNumber as deprecated.

## [2.0.0-alpha.1]

- [#21] Remove usages of deprecated method.
- [#23] FIx style.
- [#22] Fix style.
- [#14] Remove useless method.

## [1.1.6]

- [#49] Thanks to JetBrains

## [1.1.5] - 2020-06-09

- [#31] Downgrade dependencies

## [1.1.4] - 2020-05-28

- [#28] Update README.md
- [#29] Code Climate

## [1.1.3] - 2020-05-21

- [#26] Marking class PhoneNumber as deprecated.

## [1.1.1] - 2020-05-05

- [#20] Deprecated useless method.

## [1.1.0] - 2020-05-05

- [#17] Add sms events.
- [#16] test alias.
- [#13] Marking class Message as deprecated.

## [1.0.2] - 2020-04-30

- [#10] remove unused dependencies.

## [1.0.1] - 2020-04-30

- [#9] coding standard.

## [1.0.0] - 2020-04-24

- [#8] complete usage docs.

## [0.1.2] - 2020-04-24

- [#7] Make PhoneNumber notifiable.

## [0.1.1] - 2020-04-23

- [#6] Remove useless dependencies.
- [#5] remove duplicate code.

## [0.1.0] - 2020-04-22

- [#4] use easy-sms instead.

## [0.0.9] - 2020-04-17

- [#3] add message/channel/driver tests.
- [#2] add message tests.

## [0.0.1] - 2020-04-10

- [#1] coding based on overtrue/easy-sms.

[#76]: https://github.com/zingimmick/laravel-sms/pull/76
[#75]: https://github.com/zingimmick/laravel-sms/pull/75
[#74]: https://github.com/zingimmick/laravel-sms/pull/74
[#73]: https://github.com/zingimmick/laravel-sms/pull/73
[#72]: https://github.com/zingimmick/laravel-sms/pull/72
[#70]: https://github.com/zingimmick/laravel-sms/pull/70
[#69]: https://github.com/zingimmick/laravel-sms/pull/69
[#68]: https://github.com/zingimmick/laravel-sms/pull/68
[#67]: https://github.com/zingimmick/laravel-sms/pull/67
[#66]: https://github.com/zingimmick/laravel-sms/pull/66
[#65]: https://github.com/zingimmick/laravel-sms/pull/65
[#63]: https://github.com/zingimmick/laravel-sms/pull/63
[#62]: https://github.com/zingimmick/laravel-sms/pull/62
[#61]: https://github.com/zingimmick/laravel-sms/pull/61
[#60]: https://github.com/zingimmick/laravel-sms/pull/60
[#59]: https://github.com/zingimmick/laravel-sms/pull/59
[#58]: https://github.com/zingimmick/laravel-sms/pull/58
[#57]: https://github.com/zingimmick/laravel-sms/pull/57
[#55]: https://github.com/zingimmick/laravel-sms/pull/55
[#54]: https://github.com/zingimmick/laravel-sms/pull/54
[#52]: https://github.com/zingimmick/laravel-sms/pull/52
[#51]: https://github.com/zingimmick/laravel-sms/pull/51
[#50]: https://github.com/zingimmick/laravel-sms/pull/50
[#49]: https://github.com/zingimmick/laravel-sms/pull/49
[#48]: https://github.com/zingimmick/laravel-sms/pull/48
[#47]: https://github.com/zingimmick/laravel-sms/pull/47
[#46]: https://github.com/zingimmick/laravel-sms/pull/46
[#45]: https://github.com/zingimmick/laravel-sms/pull/45
[#44]: https://github.com/zingimmick/laravel-sms/pull/44
[#43]: https://github.com/zingimmick/laravel-sms/pull/43
[#42]: https://github.com/zingimmick/laravel-sms/pull/42
[#41]: https://github.com/zingimmick/laravel-sms/pull/41
[#40]: https://github.com/zingimmick/laravel-sms/pull/40
[#39]: https://github.com/zingimmick/laravel-sms/pull/39
[#38]: https://github.com/zingimmick/laravel-sms/pull/38
[#37]: https://github.com/zingimmick/laravel-sms/pull/37
[#34]: https://github.com/zingimmick/laravel-sms/pull/34
[#33]: https://github.com/zingimmick/laravel-sms/pull/33
[#32]: https://github.com/zingimmick/laravel-sms/pull/32
[#31]: https://github.com/zingimmick/laravel-sms/pull/31
[#30]: https://github.com/zingimmick/laravel-sms/pull/30
[#29]: https://github.com/zingimmick/laravel-sms/pull/29
[#28]: https://github.com/zingimmick/laravel-sms/pull/28
[#27]: https://github.com/zingimmick/laravel-sms/pull/27
[#26]: https://github.com/zingimmick/laravel-sms/pull/26
[#25]: https://github.com/zingimmick/laravel-sms/pull/25
[#24]: https://github.com/zingimmick/laravel-sms/pull/24
[#23]: https://github.com/zingimmick/laravel-sms/pull/23
[#22]: https://github.com/zingimmick/laravel-sms/pull/22
[#21]: https://github.com/zingimmick/laravel-sms/pull/21
[#20]: https://github.com/zingimmick/laravel-sms/pull/20
[#17]: https://github.com/zingimmick/laravel-sms/pull/17
[#16]: https://github.com/zingimmick/laravel-sms/pull/16
[#14]: https://github.com/zingimmick/laravel-sms/pull/14
[#13]: https://github.com/zingimmick/laravel-sms/pull/13
[#10]: https://github.com/zingimmick/laravel-sms/pull/10
[#9]: https://github.com/zingimmick/laravel-sms/pull/9
[#8]: https://github.com/zingimmick/laravel-sms/pull/8
[#7]: https://github.com/zingimmick/laravel-sms/pull/7
[#6]: https://github.com/zingimmick/laravel-sms/pull/6
[#5]: https://github.com/zingimmick/laravel-sms/pull/5
[#4]: https://github.com/zingimmick/laravel-sms/pull/4
[3.1.0]: https://github.com/zingimmick/laravel-sms/compare/3.0.1...3.1.0
[#3]: https://github.com/zingimmick/laravel-sms/pull/3
[3.0.1]: https://github.com/zingimmick/laravel-sms/compare/3.0.0...3.0.1
[3.0.0-alpha.5]: https://github.com/zingimmick/laravel-sms/compare/3.0.0-alpha.2...3.0.0-alpha.5
[3.0.0-alpha.2]: https://github.com/zingimmick/laravel-sms/compare/2.3.0...3.0.0-alpha.2
[3.0.0]: https://github.com/zingimmick/laravel-sms/compare/3.0.0-alpha.5...3.0.0
[2.3.0]: https://github.com/zingimmick/laravel-sms/compare/2.2.0...2.3.0
[2.2.0]: https://github.com/zingimmick/laravel-sms/compare/2.1.1...2.2.0
[2.1.1]: https://github.com/zingimmick/laravel-sms/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/zingimmick/laravel-sms/compare/2.0.1...2.1.0
[2.0.0-alpha.1]: https://github.com/zingimmick/laravel-sms/compare/1.1.6...2.0.0-alpha.1
[#2]: https://github.com/zingimmick/laravel-sms/pull/2
[2.0.1]: https://github.com/zingimmick/laravel-sms/compare/2.0.0-alpha.1...2.0.1
[1.1.6]: https://github.com/zingimmick/laravel-sms/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/zingimmick/laravel-sms/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/zingimmick/laravel-sms/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/zingimmick/laravel-sms/compare/1.1.1...1.1.3
[1.1.1]: https://github.com/zingimmick/laravel-sms/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/zingimmick/laravel-sms/compare/1.0.2...1.1.0
[1.0.2]: https://github.com/zingimmick/laravel-sms/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/zingimmick/laravel-sms/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/zingimmick/laravel-sms/compare/0.1.2...1.0.0
[#1]: https://github.com/zingimmick/laravel-sms/pull/1
[0.1.2]: https://github.com/zingimmick/laravel-sms/compare/0.1.1...0.1.2
[0.1.1]: https://github.com/zingimmick/laravel-sms/compare/0.1.0...0.1.1
[0.1.0]: https://github.com/zingimmick/laravel-sms/compare/0.0.9...0.1.0
[0.0.9]: https://github.com/zingimmick/laravel-sms/compare/0.0.1...0.0.9
[#82]: https://github.com/zingimmick/laravel-sms/pull/82
[#81]: https://github.com/zingimmick/laravel-sms/pull/81
[#79]: https://github.com/zingimmick/laravel-sms/pull/79
[#78]: https://github.com/zingimmick/laravel-sms/pull/78
[#77]: https://github.com/zingimmick/laravel-sms/pull/77
[#96]: https://github.com/zingimmick/laravel-sms/pull/96
[#95]: https://github.com/zingimmick/laravel-sms/pull/95
[#94]: https://github.com/zingimmick/laravel-sms/pull/94
[#93]: https://github.com/zingimmick/laravel-sms/pull/93
[#91]: https://github.com/zingimmick/laravel-sms/pull/91
[#90]: https://github.com/zingimmick/laravel-sms/pull/90
[#89]: https://github.com/zingimmick/laravel-sms/pull/89
[#88]: https://github.com/zingimmick/laravel-sms/pull/88
[#87]: https://github.com/zingimmick/laravel-sms/pull/87
[#85]: https://github.com/zingimmick/laravel-sms/pull/85
[#84]: https://github.com/zingimmick/laravel-sms/pull/84
[#83]: https://github.com/zingimmick/laravel-sms/pull/83
[#99]: https://github.com/zingimmick/laravel-sms/pull/99
[#98]: https://github.com/zingimmick/laravel-sms/pull/98
[#97]: https://github.com/zingimmick/laravel-sms/pull/97
[3.2.0]: https://github.com/zingimmick/laravel-sms/compare/3.1.0...3.2.0
[3.3.0]: https://github.com/zingimmick/laravel-sms/compare/3.2.0...3.3.0
[#113]: https://github.com/zingimmick/laravel-sms/pull/113
[#112]: https://github.com/zingimmick/laravel-sms/pull/112
[#111]: https://github.com/zingimmick/laravel-sms/pull/111
[#110]: https://github.com/zingimmick/laravel-sms/pull/110
[#109]: https://github.com/zingimmick/laravel-sms/pull/109
[#108]: https://github.com/zingimmick/laravel-sms/pull/108
[#107]: https://github.com/zingimmick/laravel-sms/pull/107
[#106]: https://github.com/zingimmick/laravel-sms/pull/106
[#105]: https://github.com/zingimmick/laravel-sms/pull/105
[#104]: https://github.com/zingimmick/laravel-sms/pull/104
[#103]: https://github.com/zingimmick/laravel-sms/pull/103
[#102]: https://github.com/zingimmick/laravel-sms/pull/102
[#101]: https://github.com/zingimmick/laravel-sms/pull/101
[#100]: https://github.com/zingimmick/laravel-sms/pull/100
