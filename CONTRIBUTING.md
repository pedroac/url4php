## Contributions are welcome

If you find a bug, or any mistake, please, commit a fix or send a report:
[https://github.com/pedroac/url4php/issues](https://github.com/pedroac/url4php/issues)

## Pull requests
* Never send commits to the master branch.
* Create a branch, eg: `feature/foo` or `fix/bar`.
* Send pull requests.
* Send one pull request for each feature.
* Commit messages must be imperative: "Create...", "Change...", etc.

## Tests
* Always run all the PHPUnit tests before pushing the code to the repository.
* Implement PHPUnit tests for new features and bug fixes.
### Running the tests
Execute from the library root:
```bash
vendor/phpunit/phpunit/phpunit --coverage-html coverage
```
Open `coverage/index.html` in a web browser to check code coverage.

## Documentation
* Follow the semver conventions: http://semver.org.
* Follow the PHPDoc conventions: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md.
* Add the `@since` directive to the introduced classes and functions.
* Make sure the README.md and the documentation is kept-updated.
* Methods descriptions must be imperative and describing expectations, like a contract: "Create...", "Change...", "... should ...", etc.
### Generating the documentation
Execute from the library root:
```bash
vendor/phpdocumentor/phpdocumentor/bin/phpdoc -d src/ -t docs/ --visibility=public --title="pedroac's URL library API Documentation"
```

## Code
* Use the PHP Code Sniffer, or a similar tool, to follow the PSR-2 Coding Standard.
* Use the PHP Mess Detector to keep the code clean.
* Keep the code readable and simple.