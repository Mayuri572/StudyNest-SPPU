"""
StudyNest SPPU - Login Log Analyzer
Detects suspicious IPs with more than 5 failed login attempts.

Usage:
    python log_analyzer.py
"""

import os
from collections import defaultdict

LOG_FILE = os.path.join(os.path.dirname(__file__), '..', 'logs', 'login_logs.txt')
THRESHOLD = 5


def analyze_logs(log_file: str, threshold: int = 5):
    failed_attempts = defaultdict(int)
    total_lines     = 0
    parse_errors    = 0

    if not os.path.exists(log_file):
        print(f"[ERROR] Log file not found: {log_file}")
        return

    with open(log_file, 'r') as f:
        for line in f:
            line = line.strip()
            if not line:
                continue
            total_lines += 1
            parts = [p.strip() for p in line.split('|')]
            if len(parts) < 4:
                parse_errors += 1
                continue

            timestamp, email, status, ip = parts[0], parts[1], parts[2], parts[3]

            if 'FAILED' in status.upper():
                failed_attempts[ip] += 1

    print("=" * 55)
    print("  StudyNest SPPU — Login Log Analyzer")
    print("=" * 55)
    print(f"  Total log entries : {total_lines}")
    print(f"  Parse errors      : {parse_errors}")
    print(f"  Suspicious threshold: {threshold} failed attempts")
    print("=" * 55)

    suspicious_found = False
    for ip, count in sorted(failed_attempts.items(), key=lambda x: -x[1]):
        if count > threshold:
            print(f"  [⚠️  SUSPICIOUS] IP: {ip}  |  Failed attempts: {count}")
            suspicious_found = True

    if not suspicious_found:
        print("  [✅ OK] No suspicious IPs detected.")
    else:
        print()
        print("  ACTION: Consider blocking the above IPs in your firewall.")

    print("=" * 55)


if __name__ == '__main__':
    analyze_logs(LOG_FILE, THRESHOLD)