@extends('layouts.app')

@push('styles')
<style>
    .fn-home {
        --ink: #17211b;
        --muted: #68736d;
        --green: #184f3a;
        --mint: #c8f3dc;
        --lime: #d9f99d;
        --cream: #f8f8f3;
        --line: #dfe4df;
        --purple: #7655d7;
        --coral: #ff815f;
        color: var(--ink);
        background: #ffffff;
        overflow-x: clip;
    }

    .fn-home h1,
    .fn-home h2,
    .fn-home h3 {
        font-family: "Manrope", "DM Sans", sans-serif;
        letter-spacing: -0.04em;
    }

    /* Hero */
    .fn-hero {
        position: relative;
        overflow: hidden;
        padding: 64px 24px 66px;
        text-align: center;
        background: radial-gradient(circle at 50% 0%, #f3faf5 0%, #fbfbf7 62%, #f4f8f4 100%);
        border-radius: 0 0 46px 46px;
    }

    .fn-hero-glow {
        position: absolute;
        width: 560px;
        height: 560px;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(80px);
    }
    .fn-glow-one {
        top: -280px;
        left: -120px;
        background: rgba(154, 230, 187, 0.42);
    }
    .fn-glow-two {
        top: -220px;
        right: -130px;
        background: rgba(196, 181, 253, 0.25);
    }

    .fn-hero-inner {
        position: relative;
        z-index: 2;
        max-width: 820px;
        margin: 0 auto;
    }

    .fn-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #e3e9e4;
        color: var(--green);
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(23, 33, 27, 0.04);
    }
    .fn-eyebrow .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2f9a69;
        box-shadow: 0 0 0 4px rgba(47, 154, 105, 0.16);
        flex-shrink: 0;
    }

    .fn-hero h1 {
        margin: 26px auto 0;
        max-width: 780px;
        font-size: clamp(38px, 6.2vw, 72px);
        line-height: 1.03;
        font-weight: 800;
        letter-spacing: -0.045em;
    }
    .fn-hero h1 span {
        color: var(--green);
        font-weight: 600;
    }

    .fn-hero-copy {
        margin: 22px auto 0;
        max-width: 560px;
        font-size: clamp(16px, 1.8vw, 19px);
        line-height: 1.6;
        color: var(--muted);
    }

    .fn-hero-actions {
        margin-top: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fn-primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 15px 25px;
        border-radius: 13px;
        background: var(--green);
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 16px 32px rgba(24, 79, 58, 0.2);
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .fn-primary-btn:hover {
        background: #123e2d;
        transform: translateY(-2px);
    }
    .fn-primary-btn.dark {
        background: var(--ink);
        box-shadow: 0 14px 28px rgba(23, 33, 27, 0.16);
    }
    .fn-primary-btn.dark:hover {
        background: #223128;
    }

    .fn-secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 22px;
        border-radius: 13px;
        background: #ffffff;
        color: var(--ink);
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--line);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .fn-secondary-btn:hover {
        border-color: #b5c1b8;
        transform: translateY(-2px);
    }

    .fn-hero-note {
        margin-top: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        color: var(--muted);
        font-size: 13px;
    }
    .fn-hero-note span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Interactive Product Stage */
    .fn-product-stage {
        position: relative;
        z-index: 2;
        max-width: 980px;
        margin: 48px auto 0;
    }

    .fn-product-tabs {
        display: inline-flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        padding: 6px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #e3e8e3;
        box-shadow: 0 12px 28px rgba(23, 33, 27, 0.05);
        max-width: 100%;
    }
    .fn-product-tabs button {
        border: 0;
        background: transparent;
        color: var(--muted);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease;
    }
    .fn-product-tabs button.active {
        background: var(--ink);
        color: #ffffff;
    }

    .fn-preview-shell {
        margin-top: 18px;
        border-radius: 26px;
        padding: 16px;
        border: 1px solid #e4e9e4;
        background: #ffffff;
        box-shadow: 0 30px 70px rgba(23, 33, 27, 0.09);
        text-align: left;
    }
    .fn-preview-shell.violet {
        background: linear-gradient(145deg, #f3eeff 0%, #ffffff 55%);
    }
    .fn-preview-shell.mint {
        background: linear-gradient(145deg, #e8f8ee 0%, #ffffff 55%);
    }
    .fn-preview-shell.orange {
        background: linear-gradient(145deg, #fff1ea 0%, #ffffff 55%);
    }

    .fn-preview-window {
        border-radius: 18px;
        border: 1px solid #ebeeeb;
        background: #ffffff;
        overflow: hidden;
    }

    .fn-window-top {
        min-height: 48px;
        padding: 8px 16px;
        border-bottom: 1px solid #eef1ee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        background: #fcfdfb;
    }
    .fn-window-dots {
        display: flex;
        gap: 6px;
    }
    .fn-window-dots i {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #dde3de;
        display: block;
    }
    .fn-window-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
    }
    .fn-window-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 999px;
        background: #e9f6ee;
        color: var(--green);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease;
    }
    .fn-window-pill:hover {
        background: #d4eedc;
    }

    /* Stage 1: Resume preview */
    .fn-resume-preview {
        padding: 24px;
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 20px;
        align-items: stretch;
    }
    .fn-preview-sidebar {
        padding: 16px;
        border-radius: 14px;
        background: #fafaf8;
        border: 1px solid #edf0ec;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .fn-side-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #828e87;
    }
    .fn-side-item {
        padding: 9px 11px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #515d56;
    }
    .fn-side-item.done {
        background: #ffffff;
        color: var(--ink);
        box-shadow: 0 2px 8px rgba(23, 33, 27, 0.04);
    }
    .fn-side-item.current {
        background: #f0ebff;
        color: #5d3ec4;
    }

    .fn-preview-canvas {
        position: relative;
        padding: 18px;
        border-radius: 14px;
        background: #f6f7f4;
        display: grid;
        place-items: center;
    }
    .fn-resume-paper {
        width: 100%;
        max-width: 470px;
        padding: 24px;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #eaedea;
        box-shadow: 0 18px 40px rgba(23, 33, 27, 0.07);
    }
    .fn-paper-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eff2ef;
    }
    .fn-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #ede7ff;
        color: #5f41c4;
        display: grid;
        place-items: center;
        font-weight: 800;
        font-size: 14px;
        flex-shrink: 0;
    }
    .fn-paper-head strong {
        display: block;
        font-size: 17px;
    }
    .fn-paper-head span {
        display: block;
        margin-top: 2px;
        color: var(--muted);
        font-size: 13px;
    }
    .fn-paper-grid {
        margin-top: 16px;
        display: grid;
        grid-template-columns: 1fr 120px;
        gap: 18px;
    }
    .fn-paper-block b {
        display: block;
        font-size: 11px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #7b867f;
    }
    .fn-paper-block p {
        margin: 6px 0 0;
        font-size: 13px;
        line-height: 1.5;
        color: #36413b;
    }
    .fn-chip-list {
        margin-top: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .fn-chip-list span {
        padding: 4px 8px;
        border-radius: 7px;
        background: #f3f1fd;
        color: #5b3ec2;
        font-size: 11px;
        font-weight: 600;
    }
    .fn-score-pill {
        position: absolute;
        right: 14px;
        bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 14px;
        background: var(--ink);
        color: #ffffff;
        box-shadow: 0 18px 34px rgba(23, 33, 27, 0.22);
    }
    .fn-score-pill > b {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--mint);
        color: var(--ink);
        display: grid;
        place-items: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .fn-score-pill strong {
        display: block;
        font-size: 12px;
    }
    .fn-score-pill span {
        display: block;
        font-size: 11px;
        color: #b7c4bc;
    }

    /* Stage 2: Invoice preview */
    .fn-invoice-preview {
        padding: 24px;
        display: grid;
        grid-template-columns: 1fr 230px;
        gap: 20px;
        align-items: start;
    }
    .fn-invoice-sheet {
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #e9ede9;
        background: #ffffff;
    }
    .fn-inv-top {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding-bottom: 18px;
        border-bottom: 1px solid #eef1ee;
    }
    .fn-inv-top strong {
        display: block;
        font-size: 18px;
    }
    .fn-inv-top span {
        color: var(--muted);
        font-size: 13px;
    }
    .fn-inv-top > div:last-child {
        text-align: right;
    }
    .fn-inv-top em {
        display: block;
        font-style: normal;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--green);
    }
    .fn-inv-rows {
        margin-top: 16px;
        display: grid;
        gap: 10px;
    }
    .fn-inv-rows div {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        background: #f9faf8;
        font-size: 14px;
    }
    .fn-inv-rows strong {
        font-variant-numeric: tabular-nums;
    }
    .fn-inv-total {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #eef1ee;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
    }
    .fn-inv-total span {
        color: var(--muted);
        font-size: 13px;
    }
    .fn-inv-total strong {
        font-size: 26px;
        color: var(--green);
    }

    .fn-invoice-options {
        display: grid;
        gap: 10px;
    }
    .fn-opt-card {
        padding: 14px;
        border-radius: 14px;
        background: #f9faf8;
        border: 1px solid #edf0ec;
    }
    .fn-opt-card span {
        display: block;
        font-size: 11px;
        color: var(--muted);
    }
    .fn-opt-card strong {
        display: block;
        margin-top: 4px;
        font-size: 14px;
    }
    .fn-swatches {
        display: flex;
        gap: 7px;
        margin-top: 8px;
    }
    .fn-swatches i {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: block;
    }

    /* Stage 3: PDF Converter preview */
    .fn-converter-preview {
        padding: 24px;
        display: grid;
        gap: 16px;
    }
    .fn-drop-zone {
        padding: 26px 18px;
        border-radius: 16px;
        border: 2px dashed #f0c2ae;
        background: #fff8f4;
        text-align: center;
    }
    .fn-drop-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 10px;
        border-radius: 13px;
        background: #ffe7dc;
        color: #cc5930;
        display: grid;
        place-items: center;
    }
    .fn-drop-zone strong {
        display: block;
        font-size: 16px;
    }
    .fn-drop-zone span {
        display: block;
        margin-top: 4px;
        color: var(--muted);
        font-size: 13px;
    }
    .fn-file-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid #ecefec;
        background: #fbfcfa;
        flex-wrap: wrap;
    }
    .fn-file-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }
    .fn-file-badge {
        padding: 7px 10px;
        border-radius: 9px;
        background: #e9f6ee;
        color: var(--green);
        font-size: 11px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .fn-file-meta strong {
        display: block;
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .fn-file-meta span {
        color: var(--muted);
        font-size: 12px;
    }
    .fn-file-done {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--green);
        font-size: 13px;
        font-weight: 700;
    }

    /* Trust Strip */
    .fn-trust-strip {
        padding: 44px 24px 10px;
        text-align: center;
    }
    .fn-trust-strip p {
        margin: 0;
        font-size: 13px;
        color: var(--muted);
    }
    .fn-logo-row {
        max-width: 900px;
        margin: 18px auto 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 32px;
        flex-wrap: wrap;
        color: #849089;
        font-weight: 700;
        font-size: 15px;
    }

    /* Tools Section (Three Cards) */
    .fn-tools-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 86px 24px 96px;
    }
    .fn-section-head {
        max-width: 660px;
        margin: 0 auto 48px;
        text-align: center;
    }
    .fn-section-tag {
        display: inline-block;
        color: var(--green);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }
    .fn-section-head h2 {
        margin: 12px 0 0;
        font-size: clamp(30px, 4.4vw, 50px);
        line-height: 1.07;
        font-weight: 800;
    }
    .fn-section-head p {
        margin: 16px 0 0;
        font-size: 17px;
        line-height: 1.65;
        color: var(--muted);
    }

    /* Keyframe Animations for Modern UI */
    @keyframes fnFloatSlow {
        0%, 100% { transform: translateY(0) rotate(-1.5deg); }
        50% { transform: translateY(-9px) rotate(-0.5deg); }
    }
    @keyframes fnFloatCard {
        0%, 100% { transform: translateY(0) rotate(3deg); }
        50% { transform: translateY(-11px) rotate(3.6deg); }
    }
    @keyframes fnPulseBadge {
        0%, 100% { transform: scale(1); box-shadow: 0 10px 24px rgba(118, 85, 215, 0.35); }
        50% { transform: scale(1.05); box-shadow: 0 14px 30px rgba(118, 85, 215, 0.55); }
    }
    @keyframes fnStampPop {
        0%, 100% { transform: rotate(-8deg) scale(1); box-shadow: 0 8px 20px rgba(24, 79, 58, 0.32); }
        50% { transform: rotate(-6deg) scale(1.06); box-shadow: 0 14px 28px rgba(24, 79, 58, 0.48); }
    }
    @keyframes fnArrowBounce {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(4px); }
    }
    @keyframes fnScanBeam {
        0% { top: 0; opacity: 0; }
        15% { opacity: 0.9; }
        85% { opacity: 0.9; }
        100% { top: 100%; opacity: 0; }
    }
    @keyframes fnBeamFlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }
    @keyframes fnParticleTravel {
        0% { transform: translateX(0); opacity: 0; }
        25% { opacity: 1; }
        75% { opacity: 1; }
        100% { transform: translateX(48px); opacity: 0; }
    }
    @keyframes fnBlobPulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.06) rotate(6deg); }
    }

    .fn-tool-cards {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
    }
    .fn-tool-card {
        border-radius: 28px;
        padding: 34px 28px 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 480px;
        overflow: hidden;
        position: relative;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
    }
    .fn-tool-card:hover {
        transform: translateY(-8px);
    }
    .fn-card-violet {
        background: linear-gradient(180deg, #f3eefc 0%, #ebe2fa 100%);
        border: 1px solid #dfd3f9;
    }
    .fn-card-violet:hover {
        box-shadow: 0 28px 56px -12px rgba(118, 85, 215, 0.22);
        border-color: #cbb4f7;
    }
    .fn-card-mint {
        background: linear-gradient(180deg, #eaf7ee 0%, #dcf3e2 100%);
        border: 1px solid #c2eccd;
    }
    .fn-card-mint:hover {
        box-shadow: 0 28px 56px -12px rgba(24, 79, 58, 0.22);
        border-color: #9fe2b2;
    }
    .fn-card-orange {
        background: linear-gradient(180deg, #fdf0e8 0%, #fae2d4 100%);
        border: 1px solid #f9d8c7;
    }
    .fn-card-orange:hover {
        box-shadow: 0 28px 56px -12px rgba(255, 129, 95, 0.22);
        border-color: #f6baa0;
    }

    .fn-card-index {
        display: inline-block;
        font-family: var(--font-mono, monospace);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.06em;
        color: rgba(23, 33, 27, 0.45);
        background: rgba(255, 255, 255, 0.6);
        padding: 2px 8px;
        border-radius: 99px;
        border: 1px solid rgba(23, 33, 27, 0.06);
    }
    .fn-tool-card h3 {
        margin: 14px 0 0;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.035em;
        line-height: 1.15;
    }
    .fn-tool-card p {
        margin: 12px 0 0;
        color: #46534b;
        font-size: 15px;
        line-height: 1.65;
    }
    .fn-card-link {
        margin-top: 22px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ink);
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: color 0.2s ease;
    }
    .fn-card-link span:last-child {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-block;
    }
    .fn-tool-card:hover .fn-card-link span:last-child {
        transform: translateX(5px);
    }

    .fn-card-art {
        position: relative;
        margin-top: 26px;
        height: 205px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    /* Card 01: Rich Realistic Resume Mockup Sheet */
    .fn-resume-sheet {
        width: 90%;
        height: 185px;
        border-radius: 18px 18px 0 0;
        background: #ffffff;
        padding: 14px 16px;
        box-shadow: 0 20px 42px rgba(118, 85, 215, 0.14), 0 0 0 1px rgba(255, 255, 255, 0.9) inset;
        border: 1px solid rgba(118, 85, 215, 0.18);
        border-bottom: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .fn-tool-card:hover .fn-resume-sheet {
        transform: translateY(-4px);
    }
    .fn-scan-beam {
        position: absolute;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #7655d7 40%, #a855f7 60%, transparent 100%);
        box-shadow: 0 0 12px #a855f7;
        animation: fnScanBeam 3.2s ease-in-out infinite;
        pointer-events: none;
        z-index: 10;
    }
    .fn-rm-header {
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f3f1;
        padding-bottom: 8px;
    }
    .fn-rm-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7655d7 0%, #906bf5 100%);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(118, 85, 215, 0.25);
    }
    .fn-rm-id {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    .fn-rm-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -0.01em;
    }
    .fn-rm-role {
        font-size: 10.5px;
        color: #718096;
        font-weight: 600;
    }
    .fn-rm-section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .fn-rm-sec-title {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #7655d7;
        text-transform: uppercase;
    }
    .fn-rm-job-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
    }
    .fn-rm-job-head strong {
        color: var(--ink);
        font-weight: 700;
    }
    .fn-rm-job-head span {
        font-size: 9.5px;
        color: #8c9b91;
    }
    .fn-rm-bullet {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 2px;
    }
    .fn-rm-bullet .fn-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #cbd5e1;
        flex-shrink: 0;
    }
    .fn-rm-line {
        height: 5px;
        border-radius: 99px;
        background: #e9ece9;
    }
    .fn-rm-tags {
        display: flex;
        gap: 5px;
        margin-top: auto;
    }
    .fn-rm-tag {
        font-size: 9px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        background: #f1edfe;
        color: #6142be;
    }

    /* Floating ATS Score Gauge Badge */
    .fn-ats-badge {
        position: absolute;
        right: 8px;
        bottom: 22px;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 7px 13px 7px 9px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(118, 85, 215, 0.25);
        box-shadow: 0 12px 28px rgba(118, 85, 215, 0.28);
        animation: fnPulseBadge 3.5s ease-in-out infinite;
        z-index: 12;
    }
    .fn-ats-circle {
        position: relative;
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
    }
    .fn-ats-ring {
        width: 30px;
        height: 30px;
        transform: rotate(-90deg);
    }
    .fn-ats-ring-bg {
        fill: none;
        stroke: #ede9fe;
        stroke-width: 3.5;
    }
    .fn-ats-ring-val {
        fill: none;
        stroke: #7655d7;
        stroke-width: 3.5;
        stroke-linecap: round;
        transition: stroke-dasharray 0.5s ease;
    }
    .fn-ats-val {
        position: absolute;
        font-size: 9.5px;
        font-weight: 900;
        color: #5e3eb5;
    }
    .fn-ats-text {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }
    .fn-ats-text strong {
        font-size: 11.5px;
        font-weight: 800;
        color: var(--ink);
    }
    .fn-ats-text span {
        font-size: 9px;
        color: #7655d7;
        font-weight: 700;
    }

    /* Card 02: Rich Realistic Invoice Mockup Sheet */
    .fn-invoice-sheet {
        width: 90%;
        height: 185px;
        border-radius: 18px 18px 0 0;
        background: #ffffff;
        padding: 14px 16px;
        box-shadow: 0 20px 42px rgba(24, 79, 58, 0.14), 0 0 0 1px rgba(255, 255, 255, 0.9) inset;
        border: 1px solid rgba(24, 79, 58, 0.18);
        border-bottom: 0;
        display: flex;
        flex-direction: column;
        gap: 9px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .fn-tool-card:hover .fn-invoice-sheet {
        transform: translateY(-4px);
    }
    .fn-im-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eef3ef;
        padding-bottom: 8px;
    }
    .fn-im-brand {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .fn-im-logo {
        width: 26px;
        height: 26px;
        border-radius: 7px;
        background: #184f3a;
        color: #ffffff;
        font-size: 10px;
        font-weight: 900;
        display: grid;
        place-items: center;
    }
    .fn-im-company {
        display: block;
        font-size: 12px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.1;
    }
    .fn-im-num {
        display: block;
        font-size: 9px;
        font-family: var(--font-mono, monospace);
        color: #718096;
        font-weight: 600;
    }
    .fn-im-date {
        font-size: 10px;
        font-weight: 600;
        color: #536058;
    }
    .fn-im-table {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .fn-im-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11px;
        color: #46534b;
    }
    .fn-im-desc {
        flex: 1;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 8px;
    }
    .fn-im-qty {
        font-size: 9.5px;
        color: #8c9b91;
        margin-right: 8px;
    }
    .fn-im-amt {
        font-weight: 700;
        color: var(--ink);
        font-variant-numeric: tabular-nums;
    }
    .fn-im-footer {
        margin-top: auto;
        border-top: 1.5px dashed #e2e8e2;
        padding-top: 6px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .fn-im-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
        font-weight: 800;
    }
    .fn-im-total span {
        color: var(--ink);
    }
    .fn-im-total strong {
        color: #184f3a;
        font-size: 14.5px;
        font-variant-numeric: tabular-nums;
    }

    /* Realistic Rubber Stamp */
    .fn-paid-stamp-v2 {
        position: absolute;
        right: 12px;
        bottom: 22px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 8px;
        background: #184f3a;
        color: #ffffff;
        font-size: 10.5px;
        font-weight: 900;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        border: 2px solid #38a169;
        box-shadow: 0 10px 24px rgba(24, 79, 58, 0.38);
        animation: fnStampPop 3.8s ease-in-out infinite alternate;
        z-index: 12;
    }

    /* Card 03: Realistic PDF Converter Pipeline Stage */
    .fn-converter-stage {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 0 12px 14px;
        position: relative;
    }
    .fn-conv-card {
        width: 96px;
        height: 124px;
        border-radius: 18px;
        background: #ffffff;
        padding: 10px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 18px 36px rgba(23, 33, 27, 0.1), 0 0 0 1px rgba(23, 33, 27, 0.05);
        position: relative;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .fn-tool-card:hover .fn-conv-card.fn-conv-docx {
        transform: translateY(-4px) rotate(-3deg);
    }
    .fn-tool-card:hover .fn-conv-card.fn-conv-pdf {
        transform: translateY(-4px) rotate(3deg);
    }
    .fn-conv-card.fn-conv-docx {
        border: 1.5px solid #bfdbfe;
        background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);
    }
    .fn-conv-card.fn-conv-pdf {
        border: 1.5px solid #fecaca;
        background: linear-gradient(180deg, #ffffff 0%, #fff5f5 100%);
    }
    .fn-conv-badge {
        font-family: var(--font-mono, monospace);
        font-size: 9.5px;
        font-weight: 900;
        padding: 2px 7px;
        border-radius: 6px;
        background: #dbeafe;
        color: #1d4ed8;
    }
    .fn-conv-badge.pdf-badge {
        background: #fee2e2;
        color: #b91c1c;
    }
    .fn-conv-icon {
        display: grid;
        place-items: center;
    }
    .fn-conv-icon.docx-color { color: #2563eb; }
    .fn-conv-icon.pdf-color { color: #dc2626; }
    .fn-conv-meta {
        text-align: center;
        line-height: 1.15;
    }
    .fn-conv-name {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: var(--ink);
    }
    .fn-conv-size {
        display: block;
        font-size: 8.5px;
        color: #718096;
    }
    .fn-conv-check {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #184f3a;
        border: 2px solid #ffffff;
        display: grid;
        place-items: center;
        box-shadow: 0 4px 10px rgba(24, 79, 58, 0.3);
    }
    .fn-conv-pipeline {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .fn-pipeline-track {
        position: relative;
        width: 58px;
        height: 6px;
        border-radius: 99px;
        background: #ffe0d3;
        overflow: hidden;
    }
    .fn-pipeline-beam {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, #ff815f, #ff5722, #ff815f);
        background-size: 200% 100%;
        animation: fnBeamFlow 1.6s linear infinite;
    }
    .fn-pipeline-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e47857;
        display: grid;
        place-items: center;
        box-shadow: 0 6px 14px rgba(228, 120, 87, 0.35);
        animation: fnArrowBounce 1.8s ease-in-out infinite;
    }
    .fn-pipeline-status {
        font-size: 9px;
        font-weight: 800;
        color: #d95a34;
        white-space: nowrap;
    }

    /* Feature Showcase */
    .fn-feature-section {
        background: var(--cream);
        padding: 104px 24px;
    }
    .fn-feature-inner {
        max-width: 1180px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 56px;
        align-items: center;
    }
    .fn-feature-visual {
        position: relative;
        min-height: 420px;
        display: grid;
        place-items: center;
    }
    .fn-visual-blob {
        position: absolute;
        width: 400px;
        height: 400px;
        max-width: 100%;
        border-radius: 44% 56% 52% 48%;
        background: radial-gradient(circle, #c8f3dc 0%, #e7f6ec 75%);
        animation: fnBlobPulse 8s ease-in-out infinite;
    }
    .fn-template-window {
        position: relative;
        z-index: 2;
        width: 430px;
        max-width: 100%;
        border-radius: 24px;
        background: #ffffff;
        border: 1px solid #e2e8e2;
        box-shadow: 0 32px 64px -12px rgba(23, 33, 27, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        overflow: hidden;
        animation: fnFloatSlow 6s ease-in-out infinite;
    }
    .fn-tw-head {
        height: 42px;
        padding: 0 16px;
        background: #fafaf8;
        border-bottom: 1px solid #eef1ee;
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .fn-tw-head i {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #dde3de;
        display: block;
    }
    .fn-template-grid {
        padding: 18px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .fn-mini-template {
        height: 182px;
        border-radius: 14px;
        border: 1.5px solid #e8ece8;
        background: #ffffff;
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .fn-mini-template:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.09);
    }
    .fn-mini-template.active {
        border-color: #184f3a;
        box-shadow: 0 0 0 3px rgba(24, 79, 58, 0.16), 0 14px 30px rgba(24, 79, 58, 0.12);
    }
    .fn-mt-tag {
        font-size: 8.5px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        padding: 2px 6px;
        border-radius: 4px;
        text-align: center;
        background: #f1f3f1;
        color: #536058;
    }
    .fn-mini-template.active .fn-mt-tag {
        background: #eaf7ee;
        color: #184f3a;
    }
    .fn-mt-check {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        background: #184f3a;
        display: grid;
        place-items: center;
        box-shadow: 0 2px 6px rgba(24, 79, 58, 0.35);
    }
    .fn-mt-head-bar {
        height: 22px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        padding: 0 6px;
        gap: 5px;
    }
    .fn-mini-template:nth-child(1) .fn-mt-head-bar { background: #ede9fe; }
    .fn-mini-template.active .fn-mt-head-bar { background: #c8f3dc; }
    .fn-mini-template.alt .fn-mt-head-bar { background: #ffe4d6; }

    .fn-mt-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(23, 33, 27, 0.2);
    }
    .fn-mt-lines {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin: 6px 0;
    }
    .fn-mt-line {
        height: 5px;
        border-radius: 99px;
        background: #edf0ed;
    }
    .fn-mt-line.accent {
        background: #cbd5e1;
    }
    .fn-mt-table {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin: 4px 0;
    }
    .fn-mt-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .fn-mt-row span {
        height: 4px;
        border-radius: 99px;
        background: #edf0ed;
    }
    .fn-mt-row strong {
        width: 14px;
        height: 4px;
        border-radius: 99px;
        background: #94a3b8;
    }
    .fn-mt-total {
        height: 6px;
        border-radius: 99px;
        background: #184f3a;
        width: 70%;
        margin-top: 2px;
    }

    .fn-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .fn-customize-card {
        position: absolute;
        right: 8px;
        bottom: 20px;
        z-index: 3;
        width: 232px;
        padding: 16px 18px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(231, 235, 231, 0.95);
        box-shadow: 0 24px 48px -8px rgba(23, 33, 27, 0.16), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        animation: fnFloatCard 5s ease-in-out infinite 0.8s;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .fn-customize-card:hover {
        box-shadow: 0 30px 60px -10px rgba(23, 33, 27, 0.22);
    }
    .fn-cust-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .fn-cust-top strong {
        font-size: 13.5px;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--ink);
    }
    .fn-live-pill {
        font-size: 8px;
        font-weight: 800;
        letter-spacing: 0.08em;
        padding: 2px 7px;
        border-radius: 99px;
        background: #eaf7ee;
        color: #184f3a;
        border: 1px solid #c2eccd;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .fn-live-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 6px #22c55e;
    }
    .fn-swatch-row {
        margin-top: 11px;
        display: flex;
        gap: 9px;
    }
    .fn-swatch-row i {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: block;
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        position: relative;
    }
    .fn-swatch-row i.active {
        transform: scale(1.15);
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #184f3a;
    }
    .fn-swatch-row i:hover {
        transform: scale(1.25);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    .fn-font-row {
        margin-top: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: var(--muted);
    }
    .fn-font-pill {
        font-size: 11px;
        font-weight: 700;
        color: var(--ink);
        background: #f1f3f1;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .fn-feature-copy h2 {
        margin: 12px 0 0;
        font-size: clamp(30px, 4.2vw, 48px);
        line-height: 1.07;
        font-weight: 800;
    }
    .fn-feature-copy > p {
        margin: 18px 0 0;
        font-size: 17px;
        line-height: 1.65;
        color: var(--muted);
    }
    .fn-feature-points {
        margin-top: 28px;
        display: grid;
        gap: 18px;
    }
    .fn-feature-points > div {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .fn-feature-points .ic {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: #ffffff;
        color: var(--green);
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 6px 16px rgba(23, 33, 27, 0.05);
    }
    .fn-feature-points strong {
        display: block;
        font-size: 15px;
    }
    .fn-feature-points span {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 14px;
    }

    /* Steps Section */
    .fn-steps-section {
        max-width: 1140px;
        margin: 0 auto;
        padding: 96px 24px;
    }
    .fn-steps {
        position: relative;
        margin-top: 58px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px;
    }
    .fn-step-line {
        position: absolute;
        top: 25px;
        left: 16%;
        right: 16%;
        height: 1px;
        background: var(--line);
    }
    .fn-steps article {
        position: relative;
        z-index: 1;
        text-align: center;
        padding: 0 12px;
    }
    .fn-steps article span {
        width: 50px;
        height: 50px;
        margin: 0 auto;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #d5ddd6;
        display: grid;
        place-items: center;
        font-family: "Manrope", sans-serif;
        font-weight: 800;
        font-size: 15px;
        color: var(--green);
        box-shadow: 0 10px 24px rgba(23, 33, 27, 0.05);
    }
    .fn-steps h3 {
        margin: 22px 0 0;
        font-size: 22px;
        font-weight: 700;
    }
    .fn-steps p {
        margin: 10px auto 0;
        max-width: 300px;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.65;
    }

    /* Testimonial */
    .fn-testimonial {
        background: #193e30;
        color: #ffffff;
        padding: 84px 24px;
        text-align: center;
    }
    .fn-testimonial-inner {
        max-width: 820px;
        margin: 0 auto;
    }
    .fn-quote-mark {
        font-family: Georgia, serif;
        font-size: 62px;
        line-height: 0.6;
        color: var(--mint);
    }
    .fn-testimonial blockquote {
        margin: 20px 0 0;
        font-family: "Manrope", sans-serif;
        font-size: clamp(22px, 3vw, 32px);
        line-height: 1.38;
        font-weight: 600;
        letter-spacing: -0.025em;
    }
    .fn-customer {
        margin-top: 30px;
        display: inline-flex;
        align-items: center;
        gap: 14px;
        text-align: left;
    }
    .fn-customer b {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--mint);
        color: var(--ink);
        display: grid;
        place-items: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .fn-customer strong {
        display: block;
        font-size: 15px;
    }
    .fn-customer span {
        color: #b7d2c4;
        font-size: 13px;
    }
    .fn-stars {
        display: inline-flex;
        gap: 3px;
        color: var(--lime);
        margin-left: 10px;
    }

    /* FAQ */
    .fn-faq-section {
        max-width: 1120px;
        margin: 0 auto;
        padding: 96px 24px;
        display: grid;
        grid-template-columns: 0.8fr 1.2fr;
        gap: 56px;
        align-items: start;
    }
    .fn-faq-intro h2 {
        margin: 12px 0 0;
        font-size: clamp(30px, 4vw, 44px);
        line-height: 1.08;
        font-weight: 800;
    }
    .fn-faq-intro p {
        margin: 16px 0 0;
        color: var(--muted);
        font-size: 16px;
        line-height: 1.65;
    }
    .fn-text-link {
        margin-top: 20px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--green);
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
    }
    .fn-faq-list {
        border-top: 1px solid var(--line);
    }
    .fn-faq-item {
        border-bottom: 1px solid var(--line);
    }
    .fn-faq-item button {
        width: 100%;
        border: 0;
        background: transparent;
        padding: 21px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        text-align: left;
        cursor: pointer;
        color: var(--ink);
        font-size: 17px;
        font-weight: 600;
    }
    .fn-faq-plus {
        font-size: 22px;
        font-weight: 400;
        color: var(--green);
        transition: transform 0.2s ease;
        flex-shrink: 0;
    }
    .fn-faq-item.open .fn-faq-plus {
        transform: rotate(45deg);
    }
    .fn-faq-answer p {
        margin: 0;
        padding-bottom: 22px;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.7;
    }

    /* CTA Section */
    .fn-cta-section {
        position: relative;
        overflow: hidden;
        max-width: 1200px;
        margin: 0 auto 36px;
        border-radius: 34px;
        padding: 84px 24px;
        text-align: center;
        background: #e2f3e8;
    }
    .fn-cta-orb {
        position: absolute;
        width: 340px;
        height: 340px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.56);
        pointer-events: none;
    }
    .fn-orb-a { top: -150px; left: -120px; }
    .fn-orb-b { bottom: -160px; right: -100px; background: rgba(187, 240, 208, 0.72); }

    .fn-cta-inner {
        position: relative;
        z-index: 2;
        max-width: 650px;
        margin: 0 auto;
    }
    .fn-cta-spark {
        width: 52px;
        height: 52px;
        margin: 0 auto 20px;
        border-radius: 16px;
        background: var(--green);
        color: #ffffff;
        display: grid;
        place-items: center;
    }
    .fn-cta-section h2 {
        margin: 0;
        font-size: clamp(30px, 4.4vw, 52px);
        line-height: 1.06;
        font-weight: 800;
    }
    .fn-cta-section p {
        margin: 16px auto 0;
        max-width: 480px;
        color: #425348;
        font-size: 17px;
        line-height: 1.6;
    }

    /* Tablet Breakpoint */
    @media (max-width: 900px) {
        .fn-resume-preview,
        .fn-invoice-preview,
        .fn-feature-inner,
        .fn-faq-section {
            grid-template-columns: 1fr;
        }
        .fn-tool-cards {
            grid-template-columns: 1fr;
        }
        .fn-tool-card {
            min-height: 410px;
        }
        .fn-score-pill {
            position: static;
            margin-top: 14px;
            width: fit-content;
        }
        .fn-cta-section {
            margin: 0 16px 24px;
        }
    }

    /* Mobile Breakpoint */
    @media (max-width: 640px) {
        .fn-hero {
            padding: 40px 16px 46px;
            border-radius: 0 0 28px 28px;
        }
        .fn-product-tabs {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .fn-product-tabs button {
            padding: 9px 6px;
            font-size: 12px;
            justify-content: center;
            gap: 5px;
        }
        .fn-preview-shell {
            padding: 10px;
            border-radius: 20px;
        }
        .fn-resume-preview,
        .fn-invoice-preview,
        .fn-converter-preview {
            padding: 12px;
            gap: 14px;
        }
        .fn-preview-canvas {
            padding: 10px;
        }
        .fn-resume-paper,
        .fn-invoice-sheet {
            padding: 16px;
        }
        .fn-paper-grid {
            grid-template-columns: 1fr;
        }
        .fn-inv-top {
            flex-direction: column;
            gap: 8px;
        }
        .fn-inv-top > div:last-child {
            text-align: left;
        }
        .fn-hero-note {
            gap: 12px;
        }
        .fn-tools-section,
        .fn-steps-section,
        .fn-faq-section {
            padding: 64px 16px;
        }
        .fn-feature-section {
            padding: 68px 16px;
        }
        .fn-feature-visual {
            min-height: 330px;
        }
        .fn-visual-blob {
            width: 280px;
            height: 280px;
        }
        .fn-template-window {
            width: 100%;
            max-width: 330px;
            transform: rotate(-1deg);
        }
        .fn-template-grid {
            padding: 12px;
            gap: 8px;
        }
        .fn-mini-template {
            height: 125px;
            padding: 9px;
        }
        .fn-customize-card {
            right: 4px;
            bottom: 4px;
            transform: scale(0.84) rotate(2deg);
        }
        .fn-steps {
            grid-template-columns: 1fr;
            gap: 36px;
            margin-top: 40px;
        }
        .fn-step-line {
            width: 1px;
            height: auto;
            top: 25px;
            bottom: 25px;
            left: 50%;
            right: auto;
        }
        .fn-steps article {
            background: #ffffff;
            padding: 12px;
        }
        .fn-testimonial {
            padding: 58px 18px;
        }
        .fn-customer {
            flex-wrap: wrap;
            justify-content: center;
            text-align: center;
        }
        .fn-stars {
            width: 100%;
            justify-content: center;
            margin-left: 0;
        }
        .fn-cta-section {
            margin: 0 12px 16px;
            padding: 58px 18px;
            border-radius: 22px;
        }
    }
</style>
@endpush

@section('content')
@php
    $converterAll = \App\Support\ConverterCatalog::all();
    $faqItems = [
        [
            'q' => 'Can I start creating without an account?',
            'a' => 'Yes. Open any studio right away, build your resume, invoice, or converted PDF, and download your file immediately without logging in. Sign up anytime to save document history across devices.',
        ],
        [
            'q' => 'How do I start an ATS CV online for free?',
            'a' => 'Open the ATS Resume Builder to pick from 17+ recruiter-vetted templates, enter your job experience and skills, or upload an existing PDF/DOCX resume to auto-fill sections in seconds with zero watermarks.',
        ],
        [
            'q' => 'Can I convert PDF to Word DOCX without losing formatting?',
            'a' => 'Yes. Formnexa\'s PDF to Word converter reconstructs native paragraphs, tables, headings, and font spacing cleanly so you can edit the resulting DOCX in Microsoft Word or Google Docs with zero formatting loss.',
        ],
        [
            'q' => 'How many document types does Formnexa support?',
            'a' => 'Alongside our Resume Builder and Smart CV Importer, you get 12 business document generators (Invoices, Receipts, Quotes, Estimates, Credit Notes, Purchase Orders, Sales Orders, Proforma Invoices, Timesheets, Work Orders, Account Statements, and Packing Slips) plus ' . count($converterAll) . ' PDF conversion tools.',
        ],
        [
            'q' => 'Will my resume work cleanly with ATS hiring software?',
            'a' => 'Every resume layout is built around clean headings, readable typography, and structured PDF output so both Applicant Tracking Systems and hiring managers read every section clearly.',
        ],
        [
            'q' => 'Are there watermarks on downloaded PDFs or converted files?',
            'a' => 'Never. Every invoice, resume, and converted file exports cleanly at crisp resolution with zero Formnexa watermarks.',
        ],
    ];
@endphp

<div class="fn-home" x-data="{ activeProduct: 'resume', openFaq: 0, dirTab: 'docs' }">

    {{-- 1. HERO + INTERACTIVE PRODUCT STAGE --}}
    <section class="fn-hero">
        <div class="fn-hero-glow fn-glow-one" aria-hidden="true"></div>
        <div class="fn-hero-glow fn-glow-two" aria-hidden="true"></div>

        <div class="fn-hero-inner">
            <span class="fn-eyebrow">
                <span class="dot" aria-hidden="true"></span>
                <span>Your documents, beautifully simple</span>
            </span>

            <h1>
                Free Invoice Maker, <span>Resume Builder &amp; PDF Converter</span>
            </h1>

            <p class="fn-hero-copy">
                Create branded invoices, quotes &amp; business documents, build an interview-ready ATS resume, or convert PDF files cleanly in seconds &mdash; all in one calm workspace.
            </p>

            <div class="fn-hero-actions">
                <a href="{{ route('invoice.studio') }}" class="fn-primary-btn">
                    <span>Create for free</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="#how" class="fn-secondary-btn">
                    See how it works
                </a>
            </div>

            <div class="fn-hero-note">
                <span>
                    <x-icon name="check-circle" class="w-4 h-4 text-[#184f3a]" />
                    No credit card
                </span>
                <span>
                    <x-icon name="check-circle" class="w-4 h-4 text-[#184f3a]" />
                    Zero watermarks
                </span>
                <span>
                    <x-icon name="shield" class="w-4 h-4 text-[#184f3a]" />
                    No sign-up required
                </span>
            </div>
        </div>

        {{-- Interactive Product Switcher --}}
        <div class="fn-product-stage" id="tools">
            <div class="fn-product-tabs" role="tablist" aria-label="Formnexa tools">
                <button type="button" role="tab" :aria-selected="(activeProduct === 'resume').toString()"
                        :class="activeProduct === 'resume' ? 'active' : ''"
                        @click="activeProduct = 'resume'">
                    <x-icon name="document-text" class="w-[17px] h-[17px]" />
                    <span>Resume Builder</span>
                </button>
                <button type="button" role="tab" :aria-selected="(activeProduct === 'invoice').toString()"
                        :class="activeProduct === 'invoice' ? 'active' : ''"
                        @click="activeProduct = 'invoice'">
                    <x-icon name="receipt" class="w-[17px] h-[17px]" />
                    <span>Invoice Maker</span>
                </button>
                <button type="button" role="tab" :aria-selected="(activeProduct === 'pdf').toString()"
                        :class="activeProduct === 'pdf' ? 'active' : ''"
                        @click="activeProduct = 'pdf'">
                    <x-icon name="arrows-right-left" class="w-[17px] h-[17px]" />
                    <span>PDF Converter</span>
                </button>
            </div>

            <div class="fn-preview-shell"
                 :class="activeProduct === 'resume' ? 'violet' : (activeProduct === 'invoice' ? 'mint' : 'orange')">
                <div class="fn-preview-window">
                    <div class="fn-window-top">
                        <div class="fn-window-dots">
                            <i></i><i></i><i></i>
                        </div>
                        <span class="fn-window-title" x-text="activeProduct === 'resume' ? 'Formnexa · Resume Studio' : (activeProduct === 'invoice' ? 'Formnexa · Invoice Studio' : 'Formnexa · File Converter')"></span>
                        <a href="{{ route('resume.builder') }}"
                           :href="activeProduct === 'resume' ? '{{ route('resume.builder') }}' : (activeProduct === 'invoice' ? '{{ route('invoice.studio') }}' : '{{ route('convert.hub') }}')"
                           class="fn-window-pill">
                            <span x-text="activeProduct === 'resume' ? 'Open Resume Studio' : (activeProduct === 'invoice' ? 'Open Invoice Maker' : 'Open Converter Hub')"></span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    {{-- Tab 1: Resume Preview --}}
                    <div x-show="activeProduct === 'resume'" x-transition.opacity.duration.150ms class="fn-resume-preview">
                        <div class="fn-preview-sidebar">
                            <div class="fn-side-label">Sections</div>
                            <div class="fn-side-item done">Personal info</div>
                            <div class="fn-side-item done">Work experience</div>
                            <div class="fn-side-item current">Skills &amp; ATS</div>
                            <div class="fn-side-item">Education</div>
                            <a href="{{ route('resume.builder', ['import' => 1]) }}" rel="nofollow"
                               class="mt-auto inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-[#f0ebff] hover:bg-[#e3d9ff] text-[#5d3ec4] text-xs font-bold transition">
                                <x-icon name="upload" class="w-[15px] h-[15px]" />
                                Import Existing CV
                            </a>
                        </div>

                        <div class="fn-preview-canvas">
                            <div class="fn-resume-paper">
                                <div class="fn-paper-head">
                                    <div class="fn-avatar">AM</div>
                                    <div>
                                        <strong>Amelia Morgan</strong>
                                        <span>Senior Product Designer &middot; London</span>
                                    </div>
                                </div>
                                <div class="fn-paper-grid">
                                    <div class="fn-paper-block">
                                        <b>Experience</b>
                                        <p>Lead Designer at Northstar &mdash; grew signup completion by 34% across web &amp; mobile.</p>
                                    </div>
                                    <div class="fn-paper-block">
                                        <b>Skills</b>
                                        <div class="fn-chip-list">
                                            <span>Figma</span>
                                            <span>Design Systems</span>
                                            <span>UX Research</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fn-score-pill">
                                <b>92</b>
                                <div>
                                    <strong>ATS-ready layout!</strong>
                                    <span>15+ curated CV templates</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Invoice Preview --}}
                    <div x-show="activeProduct === 'invoice'" x-cloak x-transition.opacity.duration.150ms class="fn-invoice-preview">
                        <div class="fn-invoice-sheet">
                            <div class="fn-inv-top">
                                <div>
                                    <strong>Acme Studio</strong>
                                    <span>Invoice #FN-2048</span>
                                </div>
                                <div>
                                    <em>Due in 14 days</em>
                                    <span>Bill to: Oakwood Media</span>
                                </div>
                            </div>
                            <div class="fn-inv-rows">
                                <div>
                                    <span>Brand identity refresh</span>
                                    <strong>$2,400</strong>
                                </div>
                                <div>
                                    <span>Website design system</span>
                                    <strong>$1,850</strong>
                                </div>
                            </div>
                            <div class="fn-inv-total">
                                <span>Total due</span>
                                <strong>$4,250.00</strong>
                            </div>
                        </div>

                        <div class="fn-invoice-options">
                            <div class="fn-opt-card">
                                <span>Currency</span>
                                <strong>USD &middot; EUR &middot; GBP &middot; PKR</strong>
                            </div>
                            <div class="fn-opt-card">
                                <span>Brand theme</span>
                                <div class="fn-swatches">
                                    <i style="background: #184f3a"></i>
                                    <i style="background: #7655d7"></i>
                                    <i style="background: #ff815f"></i>
                                    <i style="background: #17211b"></i>
                                </div>
                            </div>
                            <a href="{{ route('templates.index') }}"
                               class="fn-opt-card hover:border-[#184f3a] transition block no-underline text-[#17211b]">
                                <span>20+ Presets</span>
                                <strong class="text-[#184f3a]">Browse Invoice Themes &rarr;</strong>
                            </a>
                        </div>
                    </div>

                    {{-- Tab 3: PDF Converter Preview --}}
                    <div x-show="activeProduct === 'pdf'" x-cloak x-transition.opacity.duration.150ms class="fn-converter-preview">
                        <a href="{{ route('convert.hub') }}" class="fn-drop-zone block no-underline text-[#17211b] hover:border-[#e47857] transition">
                            <div class="fn-drop-icon">
                                <x-icon name="upload" class="w-[22px] h-[22px]" />
                            </div>
                            <strong>Drop your file to convert</strong>
                            <span>PDF, Word, Excel, PowerPoint, JPG, PNG or HTML &bull; {{ count($converterAll) }} fast tools</span>
                        </a>
                        <div class="fn-file-row">
                            <div class="fn-file-meta">
                                <span class="fn-file-badge">PDF</span>
                                <div>
                                    <strong>project-proposal-final.pdf</strong>
                                    <span>1.8 MB &middot; Clean layout preserved</span>
                                </div>
                            </div>
                            <span class="fn-file-done">
                                <x-icon name="check-circle" class="w-4 h-4" />
                                Ready to download
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Plain-text SEO Overview Strip (replaces unverified logos with clear product context) --}}
        <div class="fn-trust-strip">
            <p class="max-w-[760px] mx-auto !text-[14px] !leading-[1.7] text-[#536058]">
                Formnexa is a free online workspace for freelancers, small businesses, and job seekers. Create professional invoices, quotes, receipts, and packing slips in 24 currencies; start an ATS CV online or import an existing resume; and convert PDF files to and from Word DOCX without losing formatting, with zero watermarks and no sign-up required.
            </p>
        </div>
    </section>

    {{-- 2. THREE POWERFUL TOOLS SECTION --}}
    <section class="fn-tools-section">
        <div class="fn-section-head">
            <span class="fn-section-tag">ONE WORKSPACE</span>
            <h2>Resume Builder, Invoice Maker and PDF Converter in one workspace.</h2>
            <p>Everything you need to apply for a job, bill a client cleanly, or get a file into the exact format you need.</p>
        </div>

        <div class="fn-tool-cards">
            {{-- Card 01: Resume Builder --}}
            <article class="fn-tool-card fn-card-violet">
                <div>
                    <span class="fn-card-index">01</span>
                    <h3>ATS Resume Builder</h3>
                    <p>Start a recruiter-approved ATS CV online in minutes or import an existing PDF/DOCX resume with smart section parsing.</p>
                    <a href="{{ route('resume.builder') }}" class="fn-card-link">
                        <span>Start ATS CV Online</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                <div class="fn-card-art">
                    <div class="fn-resume-sheet">
                        <div class="fn-scan-beam" aria-hidden="true"></div>
                        <div class="fn-rm-header">
                            <div class="fn-rm-avatar">AM</div>
                            <div class="fn-rm-id">
                                <span class="fn-rm-name">Amelia Morgan</span>
                                <span class="fn-rm-role">Lead Product Designer</span>
                            </div>
                        </div>
                        <div class="fn-rm-section">
                            <div class="fn-rm-sec-title">EXPERIENCE</div>
                            <div class="fn-rm-job-head">
                                <strong>Staff Designer &bull; Stripe</strong>
                                <span>2022 &ndash; Pres.</span>
                            </div>
                            <div class="fn-rm-bullet">
                                <span class="fn-dot"></span>
                                <span class="fn-rm-line w-full"></span>
                            </div>
                            <div class="fn-rm-bullet">
                                <span class="fn-dot"></span>
                                <span class="fn-rm-line w-[75%]"></span>
                            </div>
                        </div>
                        <div class="fn-rm-tags">
                            <span class="fn-rm-tag">Figma</span>
                            <span class="fn-rm-tag">Design Systems</span>
                            <span class="fn-rm-tag">React</span>
                        </div>
                    </div>
                    <div class="fn-ats-badge">
                        <div class="fn-ats-circle">
                            <svg viewBox="0 0 36 36" class="fn-ats-ring">
                                <path class="fn-ats-ring-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="fn-ats-ring-val" stroke-dasharray="98, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <span class="fn-ats-val">98%</span>
                        </div>
                        <div class="fn-ats-text">
                            <strong>ATS Match</strong>
                            <span>17+ Themes</span>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Card 02: Invoice Maker --}}
            <article class="fn-tool-card fn-card-mint">
                <div>
                    <span class="fn-card-index">02</span>
                    <h3>Invoice &amp; Doc Maker</h3>
                    <p>Create branded invoices, quotes, receipts, packing slips &amp; 12 business documents with live taxes, currencies, and shareable links.</p>
                    <a href="{{ route('invoice.studio') }}" class="fn-card-link">
                        <span>Create Free Invoice</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                <div class="fn-card-art">
                    <div class="fn-invoice-sheet">
                        <div class="fn-im-header">
                            <div class="fn-im-brand">
                                <div class="fn-im-logo">SA</div>
                                <div>
                                    <span class="fn-im-company">Studio Apex Ltd.</span>
                                    <span class="fn-im-num">#INV-2026-084</span>
                                </div>
                            </div>
                            <div class="fn-im-date">Nov 16, 2026</div>
                        </div>
                        <div class="fn-im-table">
                            <div class="fn-im-row">
                                <span class="fn-im-desc">UI Design System &amp; Tokens</span>
                                <span class="fn-im-qty">1 &times;</span>
                                <strong class="fn-im-amt">$2,400.00</strong>
                            </div>
                            <div class="fn-im-row">
                                <span class="fn-im-desc">API Backend Integration</span>
                                <span class="fn-im-qty">1 &times;</span>
                                <strong class="fn-im-amt">$1,850.00</strong>
                            </div>
                        </div>
                        <div class="fn-im-footer">
                            <div class="fn-im-total">
                                <span>Total Due:</span>
                                <strong>$4,250.00</strong>
                            </div>
                        </div>
                    </div>
                    <div class="fn-paid-stamp-v2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>PAID IN FULL</span>
                    </div>
                </div>
            </article>

            {{-- Card 03: PDF Converter --}}
            <article class="fn-tool-card fn-card-orange">
                <div>
                    <span class="fn-card-index">03</span>
                    <h3>PDF &amp; Word Converter</h3>
                    <p>Convert PDF to DOCX without losing formatting, turn Word into crisp PDFs, or export JPG, Excel &amp; PowerPoint seamlessly.</p>
                    <a href="{{ route('convert.hub') }}" class="fn-card-link">
                        <span>Explore {{ count($converterAll) }} Converters</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                <div class="fn-card-art">
                    <div class="fn-converter-stage">
                        <div class="fn-conv-card fn-conv-docx">
                            <span class="fn-conv-badge">DOCX</span>
                            <div class="fn-conv-icon docx-color">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                </svg>
                            </div>
                            <div class="fn-conv-meta">
                                <span class="fn-conv-name">Report.docx</span>
                                <span class="fn-conv-size">1.4 MB</span>
                            </div>
                        </div>
                        <div class="fn-conv-pipeline">
                            <div class="fn-pipeline-track">
                                <div class="fn-pipeline-beam"></div>
                            </div>
                            <div class="fn-pipeline-action">
                                <svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="fn-pipeline-status">0.4s &bull; Lossless</span>
                        </div>
                        <div class="fn-conv-card fn-conv-pdf">
                            <div class="fn-conv-check">
                                <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="fn-conv-badge pdf-badge">PDF</span>
                            <div class="fn-conv-icon pdf-color">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm-9.5 8.5h-2v1h2a1 1 0 0 0 0-2zm5 2h-1.5v-3h1.5a1.5 1.5 0 0 1 0 3zm3.5-3.5h-2.5v4H17v-1.5h-1v-1h1v-1.5z"/>
                                </svg>
                            </div>
                            <div class="fn-conv-meta">
                                <span class="fn-conv-name">Report.pdf</span>
                                <span class="fn-conv-size">Ready to share</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        {{-- Compact Quick Directory for All 12 Generators, 10 Converters & Galleries --}}
        <div class="mt-14 bg-[#f8f8f3] border border-[#dfe4df] rounded-[26px] p-5 sm:p-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#dfe4df]">
                <div>
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Complete Suite Directory</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-[#17211b] mt-0.5 tracking-tight">All 12 document generators and 10 PDF converters</h2>
                </div>
                <div class="inline-flex flex-wrap gap-1.5 bg-white p-1 rounded-xl border border-[#dfe4df]">
                    <button type="button" @click="dirTab = 'docs'"
                            :class="dirTab === 'docs' ? 'bg-[#17211b] text-white' : 'text-[#536058] hover:text-[#17211b]'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                        12 Business Docs
                    </button>
                    <button type="button" @click="dirTab = 'conv'"
                            :class="dirTab === 'conv' ? 'bg-[#17211b] text-white' : 'text-[#536058] hover:text-[#17211b]'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                        {{ count($converterAll) }} PDF Converters
                    </button>
                    <button type="button" @click="dirTab = 'templates'"
                            :class="dirTab === 'templates' ? 'bg-[#17211b] text-white' : 'text-[#536058] hover:text-[#17211b]'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                        Template Galleries
                    </button>
                </div>
            </div>

            {{-- Directory Tab: 12 Business Document Generators --}}
            <div x-show="dirTab === 'docs'" class="pt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
                @foreach($generators as $slug => $meta)
                    <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}"
                       class="group flex items-center justify-between gap-2 px-3.5 py-3 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] hover:shadow-sm transition">
                        <span class="text-xs sm:text-[13px] font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">
                            {{ $meta['name'] ?? ucwords(str_replace('-', ' ', $slug)) }}
                        </span>
                        <span class="text-xs text-[#68736d] group-hover:text-[#184f3a] shrink-0">&rarr;</span>
                    </a>
                @endforeach
            </div>

            {{-- Directory Tab: PDF Converters --}}
            <div x-show="dirTab === 'conv'" x-cloak class="pt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">
                @foreach($converterAll as $cTool)
                    <a href="{{ route('convert.tool', $cTool['slug']) }}"
                       class="group flex items-center gap-2.5 px-3 py-3 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] hover:shadow-sm transition">
                        <span class="w-7 h-7 rounded-md {{ $cTool['color_bg'] }} {{ $cTool['color_text'] }} border {{ $cTool['color_border'] }} flex items-center justify-center font-mono font-bold text-[10px] shrink-0">
                            {{ $cTool['badge'] }}
                        </span>
                        <span class="text-xs font-semibold text-[#17211b] group-hover:text-[#184f3a] truncate">{{ $cTool['name'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Directory Tab: Template Galleries --}}
            <div x-show="dirTab === 'templates'" x-cloak class="pt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('resume.templates') }}"
                   class="group flex items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-[#dfe4df] hover:border-[#7655d7] transition">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-[#f0ebff] text-[#6142be] flex items-center justify-center font-display font-extrabold text-sm shrink-0">17+</span>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-[#17211b] group-hover:text-[#6142be] truncate">Resume Templates Gallery</div>
                            <div class="text-xs text-[#68736d] truncate">Minimal, Editorial, Technical &amp; ATS CV layouts</div>
                        </div>
                    </div>
                    <x-icon name="arrow-right" class="w-[18px] h-[18px] text-[#68736d] group-hover:text-[#6142be]" />
                </a>
                <a href="{{ route('templates.index') }}"
                   class="group flex items-center justify-between gap-4 p-4 rounded-2xl bg-white border border-[#dfe4df] hover:border-[#184f3a] transition">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center font-display font-extrabold text-sm shrink-0">24+</span>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-[#17211b] group-hover:text-[#184f3a] truncate">Invoice &amp; Business Presets</div>
                            <div class="text-xs text-[#68736d] truncate">Modern presets with custom branding swatches</div>
                        </div>
                    </div>
                    <x-icon name="arrow-right" class="w-[18px] h-[18px] text-[#68736d] group-hover:text-[#184f3a]" />
                </a>
            </div>
        </div>
    </section>

    {{-- 3. DESIGNED FOR YOU (FEATURE SHOWCASE) --}}
    <section class="fn-feature-section" id="features">
        <div class="fn-feature-inner">
            <div class="fn-feature-visual" aria-hidden="true">
                <div class="fn-visual-blob"></div>
                <div class="fn-template-window">
                    <div class="fn-tw-head">
                        <i></i><i></i><i></i>
                    </div>
                    <div class="fn-template-grid">
                        {{-- Mini Template 1: Modern Tech CV --}}
                        <div class="fn-mini-template">
                            <div class="fn-mt-head-bar">
                                <div class="fn-mt-dot"></div>
                                <span class="fn-mt-line w-12 !h-1.5 !bg-purple-300"></span>
                            </div>
                            <div class="fn-mt-lines">
                                <span class="fn-mt-line w-full"></span>
                                <span class="fn-mt-line w-4/5"></span>
                                <span class="fn-mt-line w-3/5 accent"></span>
                            </div>
                            <div class="flex gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-200"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-200"></span>
                            </div>
                            <span class="fn-mt-tag">Modern CV</span>
                        </div>

                        {{-- Mini Template 2: Studio Invoice (Active) --}}
                        <div class="fn-mini-template active">
                            <div class="fn-mt-check">
                                <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="fn-mt-head-bar">
                                <div class="fn-mt-dot !bg-[#184f3a]"></div>
                                <span class="fn-mt-line w-10 !h-1.5 !bg-emerald-400"></span>
                            </div>
                            <div class="fn-mt-table">
                                <div class="fn-mt-row">
                                    <span class="w-12"></span>
                                    <strong class="w-4"></strong>
                                </div>
                                <div class="fn-mt-row">
                                    <span class="w-10"></span>
                                    <strong class="w-5"></strong>
                                </div>
                            </div>
                            <div class="fn-mt-total"></div>
                            <span class="fn-mt-tag">Studio Invoice</span>
                        </div>

                        {{-- Mini Template 3: Executive Quote / PO --}}
                        <div class="fn-mini-template alt">
                            <div class="fn-mt-head-bar">
                                <div class="fn-mt-dot !bg-[#ff815f]"></div>
                                <span class="fn-mt-line w-14 !h-1.5 !bg-orange-300"></span>
                            </div>
                            <div class="fn-mt-lines">
                                <span class="fn-mt-line w-full"></span>
                                <span class="fn-mt-line w-3/4"></span>
                                <span class="fn-mt-line w-2/3 accent"></span>
                            </div>
                            <div class="w-8 h-1 bg-slate-300 rounded-full mt-1"></div>
                            <span class="fn-mt-tag">Quote &amp; PO</span>
                        </div>
                    </div>
                </div>
                <div class="fn-customize-card">
                    <div class="fn-cust-top">
                        <strong>Brand theme</strong>
                        <span class="fn-live-pill">
                            <span class="fn-live-dot"></span>
                            <span>LIVE</span>
                        </span>
                    </div>
                    <div class="fn-swatch-row">
                        <i style="background: #184f3a" class="active" title="Forest Green"></i>
                        <i style="background: #7655d7" title="Electric Violet"></i>
                        <i style="background: #ff815f" title="Coral Flame"></i>
                        <i style="background: #17211b" title="Obsidian Noir"></i>
                    </div>
                    <div class="fn-font-row">
                        <span>Typography</span>
                        <span class="fn-font-pill">Manrope &bull; Clean</span>
                    </div>
                </div>
            </div>

            <div class="fn-feature-copy">
                <span class="fn-section-tag">DESIGNED FOR YOU</span>
                <h2>Professional results. Zero design experience.</h2>
                <p>Clean templates handle margins, spacing, typography, and print alignment automatically &mdash; so every PDF you share looks polished right away.</p>

                <div class="fn-feature-points">
                    <div>
                        <span class="ic">
                            <x-icon name="sparkles" class="w-[19px] h-[19px]" />
                        </span>
                        <div>
                            <strong>Smart, balanced formatting</strong>
                            <span>Never fight broken page breaks or misaligned tables again.</span>
                        </div>
                    </div>
                    <div>
                        <span class="ic">
                            <x-icon name="layers" class="w-[19px] h-[19px]" />
                        </span>
                        <div>
                            <strong>One consistent style across documents</strong>
                            <span>Match colors and typography across resumes, invoices, quotes, and receipts.</span>
                        </div>
                    </div>
                    <div>
                        <span class="ic">
                            <x-icon name="shield-check" class="w-[19px] h-[19px]" />
                        </span>
                        <div>
                            <strong>Share securely or keep offline</strong>
                            <span>Export crisp watermark-free PDFs instantly, or share live links when needed.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. THREE EASY STEPS --}}
    <section class="fn-steps-section" id="how">
        <div class="fn-section-head">
            <span class="fn-section-tag">SIMPLE BY DESIGN</span>
            <h2>From blank page to done in three easy steps.</h2>
        </div>

        <div class="fn-steps">
            <div class="fn-step-line" aria-hidden="true"></div>
            <article>
                <span>01</span>
                <h3>Pick a tool</h3>
                <p>Choose Resume Builder, Invoice Maker, any of the 12 business generators, or a PDF converter.</p>
            </article>
            <article>
                <span>02</span>
                <h3>Make it yours</h3>
                <p>Fill in your details, import an existing CV, or drop your file &mdash; live previews update as you type.</p>
            </article>
            <article>
                <span>03</span>
                <h3>Download &amp; share</h3>
                <p>Export a print-ready, watermark-free PDF or shareable link in seconds without friction.</p>
            </article>
        </div>
    </section>

    {{-- 5. TESTIMONIAL BAND --}}
    <section class="fn-testimonial">
        <div class="fn-testimonial-inner">
            <div class="fn-quote-mark" aria-hidden="true">&ldquo;</div>
            <blockquote>
                Formnexa cut the time I spend on client proposals, invoices, and CVs in half. Everything looks consistent and takes minutes instead of an afternoon.
            </blockquote>
            <div class="fn-customer">
                <b>SK</b>
                <div>
                    <strong>Sarah Khan</strong>
                    <span>Independent Product Consultant</span>
                </div>
                <div class="fn-stars" aria-label="Rated 5 out of 5 stars">
                    @for($s = 0; $s < 5; $s++)
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    {{-- 6. FAQ ACCORDION --}}
    <section class="fn-faq-section" id="faq">
        <div class="fn-faq-intro">
            <span class="fn-section-tag">GOOD TO KNOW</span>
            <h2>Frequently asked questions about Formnexa.</h2>
            <p>Everything you need to know before generating your first resume, invoice, or converted file.</p>
            <a href="{{ route('invoice.studio') }}" class="fn-text-link">
                <span>Jump into the workspace</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="fn-faq-list">
            @foreach($faqItems as $idx => $item)
                <div class="fn-faq-item" :class="openFaq === {{ $idx }} ? 'open' : ''">
                    <button type="button"
                            @click="openFaq = openFaq === {{ $idx }} ? null : {{ $idx }}"
                            :aria-expanded="(openFaq === {{ $idx }}).toString()">
                        <span>{{ $item['q'] }}</span>
                        <span class="fn-faq-plus" aria-hidden="true">+</span>
                    </button>
                    <div class="fn-faq-answer" x-show="openFaq === {{ $idx }}" x-collapse.duration.200ms>
                        <p>{{ $item['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 6.5. LATEST GUIDES & BLOG ARTICLES --}}
    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
        <section class="max-w-[1160px] mx-auto px-4 sm:px-6 pb-20">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="fn-section-tag">GUIDES &amp; TEMPLATES BLOG</span>
                    <h2 class="font-display text-2xl sm:text-4xl font-extrabold text-[#17211b] tracking-tight mt-2">
                        Practical guides to help you get paid &amp; hired.
                    </h2>
                </div>
                <a href="{{ route('blog.index') }}" class="fn-text-link !mt-0 whitespace-nowrap">
                    <span>Read all guides</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($latestPosts as $bPost)
                    <article class="bg-[#f8f8f3] rounded-3xl border border-[#dfe4df] hover:border-[#184f3a] p-6 flex flex-col justify-between gap-4 transition">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2 text-xs font-semibold">
                                <span class="px-2.5 py-1 rounded-full bg-white text-[#184f3a] border border-[#dfe4df]">{{ $bPost->category_label }}</span>
                                <span class="text-[#68736d]">{{ $bPost->reading_time_minutes }} min read</span>
                            </div>
                            <h3 class="font-display text-lg sm:text-xl font-extrabold text-[#17211b] leading-snug">
                                <a href="{{ route('blog.show', $bPost->slug) }}" class="hover:text-[#184f3a] transition">{{ $bPost->title }}</a>
                            </h3>
                            <p class="text-xs sm:text-sm text-[#536058] leading-relaxed">{{ \Illuminate\Support\Str::limit($bPost->excerpt, 125) }}</p>
                        </div>
                        <a href="{{ route('blog.show', $bPost->slug) }}" class="text-xs font-bold text-[#184f3a] hover:underline">
                            Read guide<span class="fn-sr-only">: {{ $bPost->title }}</span> &rarr;
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 7. SOFT MINT CTA BANNER --}}
    <section class="fn-cta-section">
        <div class="fn-cta-orb fn-orb-a" aria-hidden="true"></div>
        <div class="fn-cta-orb fn-orb-b" aria-hidden="true"></div>
        <div class="fn-cta-inner">
            <div class="fn-cta-spark">
                <x-icon name="sparkles" class="w-6 h-6" />
            </div>
            <h2>Your next great document starts right here.</h2>
            <p>Try Formnexa free &mdash; build a resume, generate a branded invoice, or convert a PDF in seconds.</p>
            <div class="fn-hero-actions">
                <a href="{{ route('invoice.studio') }}" class="fn-primary-btn dark">
                    <span>Start creating &mdash; it&apos;s free</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('resume.builder') }}" class="fn-secondary-btn">
                    <span>Open Resume Builder</span>
                </a>
            </div>
        </div>
    </section>

</div>

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Organization',
            '@id' => url('/') . '#org',
            'name' => 'Formnexa',
            'url' => url('/'),
            'logo' => asset('images/formnexa-logo.png'),
        ],
        [
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'url' => url('/'),
            'name' => 'Formnexa',
            'publisher' => ['@id' => url('/') . '#org'],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqItems)->map(fn ($i) => [
                '@type' => 'Question',
                'name' => $i['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $i['a']],
            ])->values()->all(),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
@endsection
