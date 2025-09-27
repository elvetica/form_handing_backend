#!/bin/bash

# Quick deployment script for manual deployments
# Usage: ./scripts/deploy.sh

set -e

echo "🚀 Starting deployment..."

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo "❌ This is not a git repository"
    exit 1
fi

# Check for uncommitted changes
if [ -n "$(git status --porcelain)" ]; then
    echo "⚠️  You have uncommitted changes:"
    git status --short
    read -p "Continue with deployment? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Push to main branch
echo "📤 Pushing to main branch..."
git push origin main

echo "✅ Code pushed! GitHub Actions will handle the deployment."
echo "🔍 Check deployment status at: https://github.com/$(git config --get remote.origin.url | sed 's/.*://g' | sed 's/.git//g')/actions"