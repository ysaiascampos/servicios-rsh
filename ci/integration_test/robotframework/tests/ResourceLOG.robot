*** Settings ***
Library           SeleniumLibrary

*** Variables ***
${BROWSER}        chrome
${SELSPEED}       0.0s
${URL}            https://rshmunicipal.mds.cl    # Web de entrada a DataSocial
${USER}           83509198
${USER2}          175667075
${USER3}          83509198
${PASSWORD}       SIN_PASSWORD
${PASSWORD2}      56461651651651561561561561651651561561561561651651561651651616516651651651561
${PASSWORD3}      ${EMPTY}
${TIMEOUT_MS}     1000    # 1000 ms \ -> 1 seg.

*** Keywords ***
scrollView
    [Arguments]    ${element}
    scroll element into view    ${element}

deleteAllVisibleCookiesAndWait
    Delete All Cookies

close
    Close Browser

captureEntirePageScreenshot
    Capture Page Screenshot    ${TEST_NAME}_{index}.png

selectByValue
    [Arguments]    ${element}    ${value}
    Select From List by Value    ${element}    ${value}

open
    [Arguments]    ${element}
    Go To    ${element}

clickAndWait
    [Arguments]    ${element}
    Click Element    ${element}

click
    [Arguments]    ${element}
    Click Element    ${element}

sendKeys
    [Arguments]    ${element}    ${value}
    Press Keys    ${element}    ${value}

submit
    [Arguments]    ${element}
    Submit Form    ${element}

type
    [Arguments]    ${element}    ${value}
    Input Text    ${element}    ${value}

selectAndWait
    [Arguments]    ${element}    ${value}
    Select From List    ${element}    ${value}

select
    [Arguments]    ${element}    ${value}
    Select From List    ${element}    ${value}

verifyValue
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

verifyText
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

verifyElementPresent
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

verifyVisible
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

verifyTitle
    [Arguments]    ${title}
    Title Should Be    ${title}

verifyTable
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

assertConfirmation
    [Arguments]    ${value}
    Alert Should Be Present    ${value}

assertText
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

assertValue
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

assertElementPresent
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

assertVisible
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

assertTitle
    [Arguments]    ${title}
    Title Should Be    ${title}

assertTable
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

waitForText
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

waitForValue
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

waitForElementPresent
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

waitForVisible
    [Arguments]    ${element}
    Page Should Contain Element    ${element}

waitForTitle
    [Arguments]    ${title}
    Title Should Be    ${title}

waitForTable
    [Arguments]    ${element}    ${value}
    Element Should Contain    ${element}    ${value}

doubleClick
    [Arguments]    ${element}
    Double Click Element    ${element}

doubleClickAndWait
    [Arguments]    ${element}
    Double Click Element    ${element}

goBack
    Go Back

goBackAndWait
    Go Back

runScript
    [Arguments]    ${code}
    Execute Javascript    ${code}

runScriptAndWait
    [Arguments]    ${code}
    Execute Javascript    ${code}

setSpeed
    [Arguments]    ${value}
    Set Selenium Timeout    ${value}

setSpeedAndWait
    [Arguments]    ${value}
    Set Selenium Timeout    ${value}

verifyAlert
    [Arguments]    ${value}
    Alert Should Be Present    ${value}

logConsole
    [Arguments]    ${msg}
    Log    ${msg}    console=yes
    Alert Should Be Present    ${value}
