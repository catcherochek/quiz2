# Welcome to my Todo-list application

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8cd9f8588d2d4630a5f39c7bd5eee274)](https://app.codacy.com/app/jlbokass/P8-TodoList?utm_source=github.com&utm_medium=referral&utm_content=jlbokass/P8-TodoList&utm_campaign=Badge_Grade_Dashboard)
[![Build Status](https://travis-ci.com/jlbokass/P8-TodoList.svg?branch=master)](https://travis-ci.com/jlbokass/P8-TodoList)
<a href="https://codeclimate.com/github/jlbokass/P8-TodoList/maintainability"><img src="https://api.codeclimate.com/v1/badges/9243b47a72ae646fce07/maintainability" /></a>
<a href="https://codeclimate.com/github/jlbokass/P8-TodoList/test_coverage"><img src="https://api.codeclimate.com/v1/badges/9243b47a72ae646fce07/test_coverage" /></a>
 

In this project, i build a responsive todo-list app with symfony 4.3 and php 7.2. 

This work is done as part of my PHP web developer training with Openclassroom - project 8.

## Installation

1. First, download the framework, either directly or by cloning the repo.
1. Run **composer install** to install the project dependencies.
1. Configure your web server to have the **public** folder as the web root.
1. Open [env.dist](/env.dist) and enter your database configuration data.

<h4>2 Follow thoses steps :</h4>
<pre><code>composer install</pre></code>  

<h4>4 - Create database :</h4>
<pre><code>php bin/console doctrine:database:create</pre></code>

<h4>5 - Create schema :</h4>
<pre><code>php bin/console doctrine:schema:update --force</pre></code>

<h4>6 - Load fixtures :</h4>
<pre><code>php bin/console doctrine:fixtures:load</pre></code>

<h4> Code coverage :</h4>
<pre><code>http://localhost/test-coverage/index.html</pre></code>

<h4> Execute phpunit test :</h4>
<pre><code>"./vendor/bin/phpunit</pre></code>
---
