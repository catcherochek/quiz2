ToDoList
========

OpenClassrooms Project DA PHP / Symfony Project 8
- Git file link: https://github.com/jlbokass/P8-TodoList

### Presentation
The project runs on PHP 7.2 and higher
The project is based on the symfony 4.3 framework (Doctrine, Twig, Swiftmailer, and PhpUnit)
CSS side the project includes Bootstrap v4.3
JS side the project includes JQuery 3.3

### To contribute to the project:
1) Clone and Install the repository on your server (see README.md)
2) Create a branch FROM THE MASTER to your name with the function you are working on
3) Write an Issue on the changes you will make
4) Write your code ** RESPECTING GOOD PRACTICES **
5) Write Clear and Precise Commit Before Pushing Your Code
6) Update your issues
7) Make a PullRequest and wait for its validation

### Good practices :
# 1) the code
- your code must respect the minimum PSR 2
- Your code must comply with Symfony's code standards (https://symfony.com/doc/current/contributing/code/standards.html)
- Your code must comply with Symfony's code conventions (https://symfony.com/doc/3.4/contributing/code/conventions.html)

# 2) the bundles
- any PHP bundle installation must be done with ** COMPULSORY Compose **

# 3) Git
Thank you for respecting a code of good conduct and doing things in the right order
- ** New branch from master ** nominally
- Commit correctly commented
- Properly commented and documented issue
- ** pull Request MANDATORY **
- ** only the creator of the project _ (Moi) _ little merge ** on the master after revision of your code

# 4) Unit and functional tests
- PhpUnit is at your disposal to create your tests
- Any new functionality must have associated tests
- Please respect a coverage rate over 70%


# 5) File architecture
- You will respect the architecture of symfony 4.3 for your PHP files
- Your views should be in a folder corresponding to the associated route
- Your CSS will have to be in separate files per page
- called in your views in the expected block for example
- `{% block css_files%} <link href =" {{asset ('css / main.css')}} "{% endblock%}`
- Your JS must be in separate files per page
- called in your views in the expected block for example
- `{% block js_files%} <src script =" {{asset ('js / bootstrap.min.js')}} "> </ script> {% endblock%}`