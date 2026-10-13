*** Settings ***
Library           SeleniumLibrary
Resource          ResourceLOG.robot

*** Variables ***

*** Test Cases ***
TC02_RSH_LOG_LoginContraseñaError
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    click    //*[@id="loginform"]/div[3]/div/button
    captureEntirePageScreenshot
    Sleep    4s
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER3}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORD3}
    Sleep    2s
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    4s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
