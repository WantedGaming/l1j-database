# L1J Database Website Implementation Plan

## Project Overview
A PHP/HTML5/Tailwind CSS/Bootstrap5 database browser for the L1J Remastered game data with admin CRUD functionality. The site will feature a modern, card-based design with a clean, dark theme based on the specified color scheme.

**Color Scheme:**
--text: #ffffff;
--background: #030303
--primary: #121213;
--secondary: #161616;
--accent: #b84a17;



**Main Categories:**
1. Weapons - (weapon.sql, weapon_skill.sql, weapon_skill_model.sql, weapons_skill_spell_def.sql)
2. Armor - (armor.sql, armor_set.sql)
3. Items - (etcitem.sql)
4. Monsters - (npc.sql filter by impl "L1Monster" + "L1Doppelganger", mobskill.sql, mobgroup.sql)
5. Maps - (mapids.sql)
6. Dolls - (npc.sql filter by impl "L1Doll", magicdoll_info.sql, magicdoll_potential.sql)
7. NPCs - (npc.sql filter by impl "L1Blackknight", "L1Dwarf", "L1Guard", "L1HouseKeeper", "L1Merchant", "L1Npc", "L1Teleporter")
8. Skills - (skills.sql, skills_hanlder.sql, skills_info.sql, skills_passive.sql)
9. Polymorph - (polymorphs.sql)

**Required**
- navigation should be build on drop down menu style.
    - Home
    - Database
        - Weapons
        - Armors
        - Etc
    - Admin
- admin header should similar, but with return or back to site
- all pages should have hero section with search bar inside
- index page should have 3 categories per row.
- public index page should match admin dashboard with little differences
- each categories should have a list view, and a detail view, named accordingly
- folder structure should be easy to understand, without making it so complex
- all css should be on external page, NO INLINE CSS
- all function should be on external page, NO INLINE FUNCTIONS
- ALWAYS USE SQL DATA FROM DATABASE TO MAKE, UPDATE, EDIT, PAGES.
- ALWAYS CHECK EXISTING CSS STYLE, FUNCTIONS, BEFORE CREATING NEW ONES


**Detail SQL File Information**
- English Name for all sql files will be in column "desc_en" (main for naming anything)


- Finding drops from monsters (use droplist.sql file)
    - mobId = this column must match npcid from npc.sql
    - itemId = this column must match item_id from armor.sql, weapon.sql, and etcitem.sql


- Finding Monsters Spawns, Bosses, and Drops
    - spawns main files (spawnlist.sql, spawnlist_boss.sql, spawnlist_indun.sql, spawnlist_ruun.sql, spawnlist_ub.sql, spawnlist_unicorntemple.sql, spawnlist_worldwar.sql)
    - spawnlist.sql - main column is "npc_templateid" this column will match "npcid" in npc.sql where you can get detail information about the monster,  and should also match "mobId" in droplist.sql to see what it drops.
    - spawnlist_indun.sql - column "npc_id" should match "npcid" in npc.sql. column "mapId" should also match mapids.sql to get map information.
    - spawnlist_boss.sql - column "npcid" should match "npcid" in npc.sql, and should match "mobId" in droplist.sql

    Images for placeholders
    - all images can be found in assets/img/placeholders

    Images For Pages
    1. Weapons Page (we need to use weapon.sql)
    - column iconId should match image in folder +.png (assets/img/icons/icons)

    2. Armor Page (we need to use armor.sql)
    - column iconId should match image in fodder +.png (assets/img/icons/icons)

    3. Map Page (we need to use mapids.sql)
    - column pngId should match image in folder +.jpeg (assets/img/icons/maps)

    4. Items Page (we need to use etcitem.sql)
    - column iconId should match image in folder +.png (assets/img/icons/icons)

    5. Dolls Page (we need to use etcitem.sql)
    - column iconId should match image in folder +.png (assets/img/icons/dolls)

    6. NPCs Page (we need to use npc.sql)
    - column spriteId should match image folder + png/gif (assets/img/icons/npcs)

    7. Skills Page (we need to use skills_passive.sql and skill_info.sql)
    - skill_info.sql column icon needs to match in folder + .png (assets/img/icons/skills)
    - skill_passive.sql column on_icon_id needs to match in folder + png (assets/img/icons/skills)

    8. Polymorph Page (we need to use polymorphs.sql)
    - column polyid needs to match in folder + png/gif (assests/img/icons/poly)

    9. Monster Page (we need to use npc.sql)
    - column spriteId should match in folder with prefix "ms" + png/gif (assets/img/icons/monsters)
