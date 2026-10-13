*** Settings ***
Library           SeleniumLibrary
Resource          ResourceLOG.robot

*** Variables ***

*** Test Cases ***
TC03_RSH_LOG_LoginCantidadCaracteres
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    captureEntirePageScreenshot
    Sleep    1s
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER3}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORD2}
    Sleep    4s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
