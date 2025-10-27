from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()
    page.goto("http://localhost:8000/")
    page.get_by_placeholder("Username").fill("admin")
    page.get_by_placeholder("Password").fill("admin")
    page.get_by_role("button", name="Sign In").click()
    page.get_by_role("link", name="Purchase Orders").click()
    page.get_by_role("link", name="View").first.click()
    page.get_by_label("Status").select_option("Sent")
    page.get_by_role("button", name="Update Status").click()
    page.screenshot(path="jules-scratch/verification/po-status-change.png")
    browser.close()

with sync_playwright() as playwright:
    run(playwright)
