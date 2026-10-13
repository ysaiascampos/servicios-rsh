*** Settings ***
Resource    resources/resources.robot

*** Test Cases ***
Login Test Case Using Keywords
    [Setup]  Open Login Page
    Capture Page Screenshot    ${TEST NAME}_{index}.png
    click    id=login-username
    click    xpath=//div[@id='loginbox']/div/div[2]
    type    id=login-username    8.350.919-8
    type    id=login-password    asd
    submit    id=loginform
    Capture Page Screenshot    ${TEST NAME}_{index}.png
    [Teardown]  Close Browser


Login Test Case Simple
    [Setup]  Run Keywords  Open Browser  ${LOGIN URL}  ${BROWSER}
         ...              AND   Set Selenium Speed  ${SELSPEED}
         Capture Page Screenshot    ${TEST NAME}_{index}.png
         click    id=login-username
         click    xpath=//div[@id='loginbox']/div/div[2]
         type    id=login-username    8.350.919-8
         type    id=login-password    asd
         submit    id=loginform
         Capture Page Screenshot    ${TEST NAME}_{index}.png
         [Teardown]  Close Browser