@echo off
echo Creating folder structure for L1J Remastered Database Browser...

:: Root directory
set ROOT_DIR=l1j-browser
if not exist %ROOT_DIR% (
    mkdir %ROOT_DIR%
    echo Created root directory: %ROOT_DIR%
) else (
    echo Root directory already exists: %ROOT_DIR%
)

:: Public accessible files
set PUBLIC_DIR=%ROOT_DIR%\public
if not exist %PUBLIC_DIR% (
    mkdir %PUBLIC_DIR%
    echo Created public directory: %PUBLIC_DIR%
)

:: Public subdirectories
mkdir %PUBLIC_DIR%\css 2>nul
mkdir %PUBLIC_DIR%\js 2>nul
mkdir %PUBLIC_DIR%\images 2>nul
echo Created public subdirectories: css, js, images

:: Create placeholder for index.php
type nul > %ROOT_DIR%\index.php
echo Created placeholder: %ROOT_DIR%\index.php

:: PHP includes
set INCLUDES_DIR=%ROOT_DIR%\includes
if not exist %INCLUDES_DIR% (
    mkdir %INCLUDES_DIR%
    echo Created includes directory: %INCLUDES_DIR%
)

:: Includes subdirectories
mkdir %INCLUDES_DIR%\config 2>nul
mkdir %INCLUDES_DIR%\functions 2>nul
mkdir %INCLUDES_DIR%\layouts 2>nul
mkdir %INCLUDES_DIR%\components 2>nul
echo Created includes subdirectories: config, functions, layouts, components

:: Admin section
set ADMIN_DIR=%ROOT_DIR%\admin
if not exist %ADMIN_DIR% (
    mkdir %ADMIN_DIR%
    echo Created admin directory: %ADMIN_DIR%
)

:: Create admin placeholders
type nul > %ADMIN_DIR%\index.php
type nul > %ADMIN_DIR%\login.php
type nul > %ADMIN_DIR%\logout.php
echo Created admin placeholders: index.php, login.php, logout.php

:: Category directories - both public and admin
set CATEGORIES=weapons armor items monsters maps dolls npcs skills polymorph

echo Creating category directories...
for %%C in (%CATEGORIES%) do (
    :: Public category directories
    if not exist %ROOT_DIR%\%%C (
        mkdir %ROOT_DIR%\%%C
        echo Created public category: %ROOT_DIR%\%%C
    )
    
    :: Admin category directories
    if not exist %ADMIN_DIR%\%%C (
        mkdir %ADMIN_DIR%\%%C
        echo Created admin category: %ADMIN_DIR%\%%C
    )
    
    :: Create index.php, view.php in public category
    type nul > %ROOT_DIR%\%%C\index.php
    type nul > %ROOT_DIR%\%%C\view.php
    
    :: Create index.php, view.php, create.php, edit.php, delete.php in admin category
    type nul > %ADMIN_DIR%\%%C\index.php
    type nul > %ADMIN_DIR%\%%C\view.php
    type nul > %ADMIN_DIR%\%%C\create.php
    type nul > %ADMIN_DIR%\%%C\edit.php
    type nul > %ADMIN_DIR%\%%C\delete.php
)

echo.
echo Folder structure creation completed successfully.
echo.
echo Project structure:
echo - %ROOT_DIR%\
echo   - public\
echo   - includes\
echo   - admin\
echo   - [category directories]
echo.
echo Execute this script from the directory where you want to create the project.