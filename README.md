Trello-Backup
=============
[Trello-Backup](https://github.com/mattab/trello-backup) is a simple script that Backups all your [Trello.com](https://trello.com/) boards and cards, one JSON file per board, for total peace of mind. This is a simple php script which uses the Trello.com API to securely fetch all your boards and store them on your computer.

Requirements
---
This is a simple php script which requires PHP installed on your system:
`sudo apt-get install php5`

Usage
---
- Download the code in a 'trello-backup' directory with:
	`git clone https://github.com/mattab/trello-backup.git trello-backup`
- Duplicate the `config.example.php` file to `config.php` and fill in your details (as follows)
- With your browser go to: [https://trello.com/1/appKey/generate](https://trello.com/1/appKey/generate) - It will give you your public 'Key' for Trello API.
- Edit the file trello-backup/config.php and set `$key` to your 'Key', and set `$username` to your Trello.com username (without the at sign `@`)
- Then Run the script:
	`php5 trello-backup/trello-backup.php`
	It will output a URL that you can visit with your browser to get the Application Token. Visit this URL. Then click 'Allow' and copy the token string.
- Edit `config.php` and paste this token in `$application_token`.
- You are ready! Run this script will download your Trello boards:
	`php5 trello-backup/trello-backup.php`
	It will create a file named `trello-org-[OrganizationNameHere]-board-[NameHere].json` for each of your board.
Also recommended: setup a crontab to automatically backup every day or every week.

Enjoy!

How to backup several accounts
---
If you want to backup multiple Trello accounts, you can make multiple copies of `example-config.php` with different file names. Run `trello-backup.php` once for each account, specifying the path to the config file as an argument. For example, `php5 trello-backup.php account1.php`.

Why Trello-Backup?
---
Trello.com is a really wonderful free tool, but it has one technical issue 'by design': it is not [Free Software](http://www.fsf.org/) that we can self host ourselves.

Also the fine weather can turn to rain pretty quickly: We cannot trust the clouds 100%.

Plus I'm pretty sure others would like to backup their Trello data!

Who is Trello-Backup for?
---
For anyone using Trello.com who wants to ! but especially:
- if you store a lot of great ideas and tasks, 
- if you carefully plan long checklists full of unique requirements and thoughts, 
- if you have not only one board but several boards all of them containing important data,
- if you are thinking of going on a No-Internet holiday for a few weeks and wish to access your boards while offline...

What does this do in terms of clouds?
---
This little script keeps your data out of the clouds!

What is Trello?
---
Trello is a free web-based project management application made by Fog Creek Software.
Trello uses a paradigm for managing projects known as kanban, a method that had originally been popularized by Toyota in the 1980s for supply chain management. Projects are represented by boards, which contain lists (corresponding to task lists). Lists contain cards (corresponding to tasks). Cards are supposed to progress from one list to the next (via drag-and-drop), for instance mirroring the flow of a feature from idea to implementation. Users can be assigned to cards. Users and boards can be grouped into organizations.

Source: Trello on [Wikipedia](http://en.wikipedia.org/wiki/Trello)

Credits
---
This is my first Github project!
 ~ [Matthieu Aubry](http://matthieu.net/) 

Kuddos to [Zander](https://github.com/zph/) on Github for his help, when I was trying to use his [trello-archiver](https://github.com/zph/trello-archiver).

This script officially started from [this Gist](https://gist.github.com/4498847)!

The README Is longer than the script - I'm also practising Markdown.


<!-- Piwik Image Tracker -->
<img src="http://demo.piwik.org/piwik.php?idsite=41&amp;rec=1&amp;action_name=Readme" style="border:0" alt="" />
<!-- End Piwik -->

