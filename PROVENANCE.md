# MKPortal R1.0.2 — provenance

This branch holds the **complete MKPortal R1.0.2 distribution**, recovered
2026-07-29. It replaces the M1.x-era tree that this fork previously carried,
which is preserved on the **`OG`** branch.

## What R1.0.2 is

RusMKPortal was not a translation of MKPortal — it was the **successor
project** that continued development after the Italian team closed
mkportal.it around 2013. The release ladder is recorded in the project's own
upgrade script, `upload/mkportal/upgrades/upgrade102.php`:

    M1.0 → M1.1 Rc1 → M1.1 → M1.1.1 → M1.1.2 → M1.1.2b
         → C1.2 beta1 → C1.2 beta3 → C1.2 rc1 → C1.2 rc2 → C1.2
         → C1.2.1 → C1.2.2 R0.0.2          ← source comment: "Added by Kimi in C1.2.2"
         → R0.0.3 → R1.0.1 → R1.0.2        ← this release

So R1.0.2 is **C1.2.2 plus three subsequent releases** — the closest recovered
descendant of C1.2.2, which itself remains unrecovered.

## Where it came from

`rusmkportal.ru` is still online but sits behind Cloudflare. The package was
recovered instead from the Wayback Machine's capture of that site's IPB
forum download section, which archived actual file bodies:

    http://www.rusmkportal.ru/support/index.php?app=downloads&module=display&section=download&do=confirm_download&id=1
    captured 2013-05-31 05:59:58

    sha256  6a5a3b81a6bf6342fd5dcb2bae233da1d166f249c88a717c7ccaef2501d52ed2
    size    8,161,467 bytes · 3,387 entries · zip verified intact

Committed unmodified apart from this file. Original package layout is
preserved: `doc/`, `license.txt`, `readme.htm`, `upload/`.

## What changed since M1.x (the `OG` branch)

|                | M1.x (`OG`)                          | R1.0.2 (here)                                                    |
| -------------- | ------------------------------------ | ---------------------------------------------------------------- |
| Files / size   | 1,029 · 7.4 MB                       | 2,960 · 23 MB                                                      |
| Boards         | IPB, Oxygen, phpBB2, SMF, vB         | AEF, IPB, IPB13, **IPB3**, MyBB, PHPBB, **PHPBB3**, SMF, **SMF2**, VB |
| Modules        | 11                                   | 17                                                                 |
| Blocks         | 22                                   | 33                                                                 |
| Languages      | English, Francais, Italiano          | + **Russian**, UTF8, English_Reference                             |
| Editor         | TinyMCE only                         | bbeditor, FCKeditor, NicEdit, flashplayer                          |

New modules: `rss`, `contact`, `recommend`, `poll` (promoted from a block),
plus `ajaxout` / `rajax` providing an AJAX layer M1.x had no equivalent for.
New blocks include `AjaxIPBpost`, `AjaxIPB3post`, `AjaxSiteMonitor`, `voting`,
`commentblock`, `rss_simplepie`, `last_reviews`. Admin gains `ad_rss`,
`ad_contact`, `ad_recommend`, `ad_voting`, `ad_langs`, `ad_categories`,
`ad_phpinfo` and `sp_compatibility_test.php`. Board drivers moved from flat
`*_board_functions.php` files into per-board directories, each gaining a third
`*_out.php` layer. Oxygen support was dropped.

Visually, the `default` template roughly doubles: 105 → 203 images, CSS
10.1 KB → 16.3 KB. New here are `style.css.rtl` and `tpl_main.php.rtl`
(right-to-left support, absent in M1.x), `stylecp.css` for separately themed
control panels, and `mkp.ext.ajax.js`. A third bundled skin, `rusmkportal`,
ships alongside `default` and `Forum`. The 22-icon `atb_*` toolbar strip is
unchanged, so core navigation still looks familiar.

## Known gaps

- **No English documentation.** R1.0.2 ships `doc/fr`, `doc/it`, `doc/ru` only.
  The English C1.2 docs are on the `OG` branch under `doc/en/`, mirrored
  separately from `duelserver09.freehostia.com`. They describe C1.2 rather than
  R1.0.2, but no closer English text has been found.
- **The bundled docs lag the code.** `doc/*/install.htm` still lists the C1.2
  board matrix (IPB 1.3/2.3, MyBB 1.2.x, SMF 1.1.x, AEF 1.0.6) even though
  working IPB3 and SMF2 drivers ship in `upload/mkportal/include/`.
- **C1.2.1 and C1.2.2 themselves are still missing.** mkportal.it never had a
  package captured by the Wayback Machine, archive.org holds no MKPortal items,
  and the mirror network (mkportal.es, .fr, .nl, .se, .me, .gen.tr,
  mkportal-support.de) is entirely offline.

## Related material not committed here

13 skins and 10 modules/snippets were recovered from the same Wayback capture
of rusmkportal.ru and are held outside this repository at
`~/mkportal-archive/`. Skins: BWFT, cesurturkv3, festival, game_template,
gamigo, minig, mkDad_Fantasy, simple_black_arbigon, simple_dark_arbigon,
simple_dark_arbigon_2, simple_white_arbigon, white_christmas, wow. Modules
include ajaxminichat, mkmedia (C1.2 rc1), and a radio module with Russian
language files.

## Security

This is abandoned PHP 4-era code with public exploit advisories and no patches
since roughly 2013. Treat it as an archival artifact. Do not expose an
installation to the public internet.
