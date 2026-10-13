*** Settings ***
Library           SeleniumLibrary
Resource          ResourceLOG.robot

*** Variables ***

*** Test Cases ***
TC04_RSH_LOG_RutFueraBD
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    captureEntirePageScreenshot
    Sleep    1s
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER2}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORD2}
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    7s
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/div[2]/div/button
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
