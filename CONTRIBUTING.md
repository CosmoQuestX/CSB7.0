# How to contribute

If you are interested in contributing, feel free to fork and make pull reguests based on the currently open issues. 
If you found a bug, please report an issue on that. Likewise, if you are missing a feature, please report an issue describing the feature you want. 
Lastly, if you have found a security-related issue, please see the [security policy](/blob/master/SECURITY.md) on how to proceed 
If you're unsure where to start, catch us on [Discord](https://discord.gg/X8rw4vv) in the channel "#volunteers-reporting-for-duty" 


## Submitting changes

For small bugfixes, please send a [GitHub Pull Request against the master branch](/pull/new/master) with a clear list of what you've done (read more about [pull requests](http://help.github.com/pull-requests/)). 
For larger pull requests it might be better to contact us via Discord first to test it in a separate branch.

Please make sure all of your commits are atomic (one feature or bugfix per commit).
Always write a clear log message for your commits. One-line messages are fine for small changes, but bigger changes should include a description of how and why you changed things. 
If if addresses an open issue, please include the issue number in the description of the commit.

Lastly, if you send a pull request, please include a description of what you are submitting in the pull request itself, summarizing what you have done (and please, don't just copy the messages of all the commits as a comment).

## Coding conventions

There is an [Editorconfig](https://editorconfig.org/) file included in the repository, so if your IDE or Editor supports that, you're good to go!

If your IDE or Editor does not support Editorconfig, here's a quick rundown:

  * We indent using four spaces
  * An exception to that are the SASS/SCSS files, which are indented using two spaces
  * If you can, please set your Editor to use "lf" as an EOL character and use UTF-8

Besides of that, here's some dos and donts

  * Do try to make your code as readable as you can, and try to avoid long lines especially in comments.
  * Do comment, rather comment more than less. 
  * Do comment functions in a PHPDOC style - we're trying to, and it would be great if you could, too
  * Do give functions and variables names that make sense in the context (instead of naming them $temp1, $temp2, $temp3 etc.)
  * Don't assume that people can just figure out what you intended just because of the code you wrote.



