# Arlo's Notation

All commit messages should follow Arlo's Notation.

```
------ Risk ------          ------ Action ------
.   Provable                r   Refactoring
-   Tested                  e   Environment (non-code)
!   Single Action           d   Documentation
@   Other                   t   Test only
                            F   Feature
                            B   Bugfix
```

## Examples

- `! B fixed spelling on label`
- ```
  . d added extra notes in installer SMTP tab

  - Added `(recommended)` to TLS label
  - Added a note about a `Test Connection` edge-case [Port 22]
  - Repositioned the `Send Test Email` button
  ```
