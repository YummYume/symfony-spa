# Contributing to the project

> **Note**  
> You can (and should) modify this section to your needs.

How to contribute to the project?
There are 2 ways of contributing: reporting a bug or proposing a feature, and making changes to the code.

## Content

- [Contributing to the project](#contributing-to-the-project)
  - [Content](#content)
  - [How to contribute by reporting a bug/proposing a feature?](#how-to-contribute-by-reporting-a-bugproposing-a-feature)
  - [How to contribute to the code?](#how-to-contribute-to-the-code)
    - [Branches](#branches)
    - [Prerequisites](#prerequisites)
    - [Launch the project locally](#launch-the-project-locally)
    - [What do I need to check before making a PR?](#what-do-i-need-to-check-before-making-a-pr)
    - [Available services](#available-services)
    - [Make commands](#make-commands)
    - [Environment files](#environment-files)
    - [The database](#the-database)
    - [IDEs](#ides)
      - [VSCode](#vscode)
    - [Additional documentation](#additional-documentation)
    - [Good to know](#good-to-know)

## How to contribute by reporting a bug/proposing a feature?

You can [open an issue](https://github.com/YummYume/symfony-spa/issues/new/choose) with the appropriate template, but make sure a similar issue doesn't already exist.

If no template satisfies you, feel free to create one from scratch, but do include as many details as possible. Never make an empty issue.

## How to contribute to the code?

You should always create a new branch, with an explicit name, and create a pull request once done.

Everything you need to know to use this project and contribute to it is written below.

### Branches

- The [master](https://github.com/RankyList/ranky-list/tree/master) branch is **not** the default branch. It is used to represent what is currently in _production_.
- The [develop](https://github.com/RankyList/ranky-list) branch is the default branch. This is the default target branch for pull requests and new branches.
- Other branches are created freely but should respect a certain name coherence, for example, if you are adding a new feature, your branch name should look like `feature/my-feature`.
- **Always** make sure that your branch is up to date with its parent branch before submitting a pull request.

### Prerequisites

- [Docker](https://www.docker.com/) (with Docker Compose).
- [Git](https://git-scm.com/).
- This is obvious, but having experience with node/javascript is a must.

### Launch the project locally

> **Warning**  
> Docker is required

- Fork or clone the project with `git@github.com:YummYume/symfony-spa.git` _OR_ `git@github.com:your-username/symfony-spa.git` if you forked the project.
- Create your own branch from `develop` or any branch other than `master` (eg: `feature/my-feature`).
- Launch the project using `make start` if you have Make installed, or `docker compose build && docker compose up -d` otherwise (you may need additional steps to have the project working, check what's inside the Makefile).
- Go to `http://localhost` to access the app.
- From there, you can add your own code and tests in the appropriate folders.

### What do I need to check before making a PR?

Make sure of the following :

- Your PR is up to date with its parent branch
- Your PR includes tests (not always needed but is very recommended)
- Your PR describes everything the reviewers need to know.
- Your PR follows the [code of conduct](CODE_OF_CONDUCT.md).
- The linter does not fail (or at least not because of your PR).
- You avoided the usage of an external dependency (only use one if you need to).

### Available services

List of all the available services (and how to access them).

| Service           | Description                                                                                                                  | Access                                                          |
| ----------------- | ---------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------- |
| `nginx`           | Serves as a proxy for the Symfony app. Also serves static files inside the `public` folder.                                  | [localhost:80](http://localhost:80)                             |
| `php`             | The service running PHP and serving the Symfony app.                                                                         |                                                                 |
| `encore`          | Serves assets files directly by using the encore webpack dev server. Also allows hot reloading. Is only used in development. | [localhost:9090](http://localhost:9090)                         |
| `db`              | Service for MariaDB, used by the app.                                                                                        |                                                                 |
| `phpmyadmin`      | GUI to access and interact with the `db` service easily. Is only used in development.                                        | [localhost:8080](http://localhost:8080)                         |
| `mailcatcher`     | Serves as a SMTP to catch emails sent during development. Is only used in development.                                       | [localhost:1080](http://localhost:1080)                         |
| `rabbitmq`        | Service used to queue and execute asynchronously processes in the Symfony app, such as sending emails.                       | [localhost:15672](http://localhost:15672)                       |
| `redis`           | The Redis database service, used mainly for caching purposes, and for Symfony sessions.                                      |                                                                 |
| `redis-commander` | GUI to access and interact with the `redis` service easily. Is only used in development.                                     | [localhost:8081](http://localhost:8081)                         |
| `mercure`         | The Mercure server, used for real-time communication.                                                                        | [localhost:3000](http://localhost:3000/.well-known/mercure/ui/) |

### Make commands

List of the available make commands.

| Command            | Description                                                                                                                                 |
| ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------- |
| `start`            | Builds the containers and starts them. Also installs composer dependencies and resets your db.                                              |
| `build`            | Builds the containers.                                                                                                                      |
| `build-no-cache`   | Builds the containers without cache.                                                                                                        |
| `up`               | Starts the stopped containers.                                                                                                              |
| `up-recreate`      | Recreates and starts the stopped containers.                                                                                                |
| `stop`             | Stops all running containers.                                                                                                               |
| `down`             | Removes all containers.                                                                                                                     |
| `ssh`              | Runs `sh` in the `php` container.                                                                                                           |
| `ssh-encore`       | Runs `sh` in the `encore` container.                                                                                                        |
| `ssh-redis`        | Runs `sh` in the `redis` container.                                                                                                         |
| `ssh-db`           | Runs `sh` in the `db` container.                                                                                                            |
| `composer`         | Runs `composer install` in the `php` container.                                                                                             |
| `yarn`             | Runs `yarn install` in the `encore` container.                                                                                              |
| `perm`             | Runs different commands depending on your OS to ensure you have write permission on the project's files.                                    |
| `db`               | Runs `db-drop`, `db-create`, `migration` and `fixtures`.                                                                                    |
| `db-create`        | Creates a new database, if it doesn't already exist.                                                                                        |
| `db-drop`          | Drops the current database, if it exists.                                                                                                   |
| `schema`           | Syncs your database with the current schema in the app. Prefer using migration. Use it only for quick tests.                                |
| `migration`        | Runs all pending migrations.                                                                                                                |
| `migration-diff`   | Creates a new migration file with all the changes you made in the app's entities.                                                           |
| `fixtures`         | Runs all the fixtures available.                                                                                                            |
| `db-test`          | Creates a app_test db for tests.                                                                                                            |
| `rabbitmq-consume` | Consumes the Rabbitmq queue. You normally won't need to use this command as the `php` container already takes care of it in the background. |
| `list-containers`  | Lists all running containers.                                                                                                               |
| `healthcheck-db`   | Shows the current healthcheck for the `db` container.                                                                                       |
| `healthcheck-php`  | Shows the current healthcheck for the `php` container.                                                                                      |
| `logs`             | Shows logs of all the running containers.                                                                                                   |
| `logs-php`         | Shows logs for the `php` container.                                                                                                         |
| `logs-encore`      | Shows logs for the `encore` container.                                                                                                      |
| `cc`               | Clears the Symfony cache.                                                                                                                   |
| `lint`             | Runs `php-cs-fixer`, `eslint` and `prettier`.                                                                                               |
| `php-cs-fixer`     | Runs `php-cs-fixer` in the `php` container without fixing.                                                                                  |
| `eslint`           | Runs `eslint` in the `encore` container without fixing.                                                                                     |
| `prettier`         | Runs `prettier` in the `php` container without fixing.                                                                                      |
| `fix`              | Runs `php-cs-fixer-fix`, `eslint-fix` and `prettier-fix`.                                                                                   |
| `php-cs-fixer-fix` | Runs `php-cs-fixer` in the `php` container.                                                                                                 |
| `eslint-fix`       | Runs `eslint` in the `encore` container.                                                                                                    |
| `prettier-fix`     | Runs `prettier` in the `php` container.                                                                                                     |
| `test`             | Runs tests in the `php` container.                                                                                                          |
| `test-create`      | Creates a new test.                                                                                                                         |
| `start-ci`         | Starts the containers to run for the CI. You should never need to run this command.                                                         |

### Environment files

We use `.env` files to store sensitive data or data that needs to be changed for certain environments (like Windows users).

To override a value from a `.env` file, create a `.env.local` file (which will not be committed on GitHub). Do not directly modify the `.env` file.

### The database

This project currently uses MariaDB as database. You can easily access it with a friendly GUI thanks to PHPMyAdmin by going to `localhost:8080`.

Assuming you did not change the credentials in the root `.env` file :

- Server : `db` (this is the name of the service for your database),
- User : `root`,
- Password : `root`.

You can then access the current database named `app`. Another table called `app_test` can also appear after running tests.

> **Note**  
> The database is kept on your host in a `data` folder at the root of the project.

### IDEs

You can use any IDE you'd like. We of course recommend VSCode, which this project already includes a `settings.json` file for with everything configured.

Feel free to add documentation for your IDE here.

#### VSCode

The configuration for VSCode is already set and ready to use in `.vscode/settings.json`. Simply copy it from the `.vscode.dev` folder.

Of course, it will require some VSCode extensions to work properly :

- [Docker](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker) for Dockerfile and docker-compose files support,
- [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode) for formatting generic and template files (twig, JSON, SCSS, etc...),
- [PHP-CS-Fixer](https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer) for PHP files linting,
- [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint) for Typescript and Svelte files linting.

That should be all you need.

> **Note**  
> For the PHP-CS-Fixer extension, you will need to change the value for `php-cs-fixer.executablePath` and add the absolute path to the project on your disk.  
> You can adjust the other settings however you want.

### Additional documentation

> **Note**  
> All the additional documentation is located in the `docs` folder.

All the documentation you need to safely develop on this project. Feel free to add anything you think is missing!

### Good to know

Your PR will always be checked as soon as possible. You need to make sure you do everything you can to make it easier to review it.

You can look at other PRs if you are not too sure about something. Also, make sure to link any issue related to the PR you created.

Finally, thank you for the interest you have in this project! Don't worry, everyone here is very friendly.
