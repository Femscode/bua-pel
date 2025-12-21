import fs from "fs-extra";
import path from "path";
import { glob } from "glob";
import postcss from "postcss";
import cheerio from "cheerio";

// Lazy-load PostCSS plugins to speed cold starts
const autoprefixer = (await import("autoprefixer")).default;
const pxtorem = (await import("postcss-pxtorem")).default;
const presetEnv = (await import("postcss-preset-env")).default;
const cssnano = (await import("cssnano")).default;

const ROOT = process.cwd();
const INPUT = path.join(ROOT, "input");
const DIST = path.join(ROOT, "dist");
const STYLES = path.join(ROOT, "styles");

async function ensureDirs() {
  await fs.ensureDir(INPUT);
  await fs.ensureDir(DIST);
  await fs.ensureDir(STYLES);
}

async function readCSSFiles() {
  const cssFiles = await glob(["input/**/*.css", "!input/**/reset.css"]);
  const parts = [];
  for (const file of cssFiles) {
    const css = await fs.readFile(file, "utf8");
    parts.push(`/* ---- ${path.relative(ROOT, file)} ---- */\n` + css);
  }
  return parts.join("\n\n");
}

async function buildCSS() {
  const baseCss = await fs.readFile(path.join(STYLES, "base.css"), "utf8");
  const exportedCss = await readCSSFiles();
  const combined = baseCss + "\n\n/* ===== Exported CSS ===== */\n" + exportedCss;

  const result = await postcss([
    presetEnv({ stage: 1 }),
    pxtorem({ rootValue: 16, propList: ["*"], minPixelValue: 1.1 }),
    autoprefixer(),
    cssnano({ preset: "default" })
  ]).process(combined, { from: undefined });

  await fs.outputFile(path.join(DIST, "styles.css"), result.css, "utf8");
  console.log("✓ CSS built -> dist/styles.css");
}

function ensureViewport($) {
  const hasViewport = $('meta[name="viewport"]').length > 0;
  if (!hasViewport) {
    $("head").prepend(
      '<meta name="viewport" content="width=device-width, initial-scale=1">'
    );
  }
}

function ensureCharset($) {
  const hasCharset = $('meta[charset]').length > 0;
  if (!hasCharset) {
    $("head").prepend('<meta charset="UTF-8">');
  }
}

function ensureCSSLink($) {
  const hasLink = $('link[href$="styles.css"]').length > 0;
  if (!hasLink) {
    $("head").append('<link rel="stylesheet" href="styles.css">');
  }
}

function wrapInContainer($) {
  // If there is no top-level container, wrap the main visible content
  const bodyChildren = $("body").children();
  if (bodyChildren.length === 1 && bodyChildren.first().hasClass("container")) return;
  // Avoid double-wrapping headers/footers already semantic
  const wrapper = $('<div class="container"></div>');
  bodyChildren.appendTo(wrapper);
  $("body").append(wrapper);
}

function addLandmarks($) {
  // Light heuristic: if there's a top nav-like element, give it a <nav> role
  $('[class*="nav"], [id*="nav"]').each((_, el) => {
    const $el = $(el);
    if ($el.closest("nav").length === 0) {
      $el.attr("role", "navigation");
    }
  });
  // Ensure a main landmark
  if ($("main").length === 0) {
    // Convert the container into <main> if appropriate
    const cont = $(".container").first();
    if (cont.length) {
      cont.attr("role", "main");
    }
  }
}

async function processHTML() {
  const htmlFiles = await glob(["input/**/*.html"]);
  if (htmlFiles.length === 0) {
    console.warn("No HTML files in /input. Place your exported files in the input folder.");
    return;
  }
  for (const file of htmlFiles) {
    const rel = path.relative(path.join(ROOT, "input"), file);
    const out = path.join(DIST, rel);
    await fs.ensureDir(path.dirname(out));
    const html = await fs.readFile(file, "utf8");
    const $ = cheerio.load(html);
    ensureCharset($);
    ensureViewport($);
    ensureCSSLink($);
    wrapInContainer($);
    addLandmarks($);
    await fs.writeFile(out, "<!doctype html>\n" + $.html(), "utf8");
    console.log("✓ HTML processed ->", path.relative(ROOT, out));
  }
}

async function copyAssets() {
  // Copy everything except HTML/CSS into dist as-is (images, fonts, scripts)
  const assets = await glob(["input/**/*", "!input/**/*.html", "!input/**/*.css"]);
  for (const src of assets) {
    const rel = path.relative(path.join(ROOT, "input"), src);
    const out = path.join(DIST, rel);
    await fs.ensureDir(path.dirname(out));
    await fs.copyFile(src, out);
  }
  console.log("✓ Assets copied");
}

async function main() {
  await ensureDirs();
  await buildCSS();
  await processHTML();
  await copyAssets();
  console.log("\nAll done. Open dist/*.html in your browser.");
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
