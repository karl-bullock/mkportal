# Provenance of this `doc/` tree

**These docs are for MKPortal C1.2 — they do NOT match the code in this repository.**

## Where these came from

Mirrored on 2026-07-29 from an abandoned free-hosting account that still had
directory listing enabled:

    http://duelserver09.freehostia.com/MKportal_C12_final/doc/

That host is unmaintained and may disappear without notice. This copy was taken
so the documentation survives.

Retrieved with `wget -r -np`. 98 files: `en/`, `fr/`, `it/` plus the shared
`files/` directory (CSS, images, and the per-board code snippets under
`files/code/`). Nothing was edited; only this file was added.

The same mirror also held the C1.2 package-root `readme.htm` (dated 2008-01-07)
and `license.txt` (2008-05-29), which are not included here.

## Version mismatch — read this before using them

The code in this repository is **MKPortal M1.x**, not C1.2:

- copyright headers read `(c) 2004-2005`
- `include/` contains board drivers for IPB, Oxygen, phpBB 2, SMF and vBulletin only

These C1.2 docs describe a later release supporting:

    SMF 1.1.x, IPB 1.3.x, IPB 2.3.x, vB 3.7.x,
    phpBB 2.0.x, phpBB 3.x, MyBB 1.2.x, AEF 1.0.6

MyBB, AEF and phpBB 3 support does not exist in this code tree. Installation and
upgrade steps here refer to files and directories that this repository does not
contain.

Treat them as reference material for a version whose source has not been
recovered, not as instructions for the accompanying code.

## What replaced what

The previous `doc/` tree (the M1.x-era `doc/html/install/` and
`doc/html/upgrade/` documentation, 53 files) matched the code and was removed by
this commit. It remains in git history:

    git show HEAD~1:doc/index.html
    git checkout HEAD~1 -- doc      # to restore it

## Status of the C1.2.1 / C1.2.2 releases

Both were released (confirmed by the archived mkportal.it homepage of
2010-08-20, which announces C1.2.2 and work on C1.2.3). Neither package has been
located. mkportal.it closed around 2013; no `.zip`/`.rar` was ever captured by
the Wayback Machine, archive.org holds no MKPortal items, and the mirror network
(mkportal.es, .fr, .nl, .se, .me, .gen.tr, mkportal-support.de) is entirely
offline.
