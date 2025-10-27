from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Login
    page.goto("http://localhost:8000/index.php")
    page.fill("input[name='username']", "admin")
    page.fill("input[name='password']", "admin")
    page.click("button[type='submit']")
    page.wait_for_url("http://localhost:8000/debug_session.php")

    # Get the content of the debug page
    content = page.content()
    print(content)

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
