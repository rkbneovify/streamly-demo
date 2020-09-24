//
//  TimeComparable.swift
//  Streamly
//
//  Created by Rohit Singh on 9/24/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation


struct Time: Comparable {
    var hour = 0
    var minute = 0

    init(hour: Int, minute: Int) {
        self.hour = hour
        self.minute = minute
    }
    init(_ date: Date) {
        let calendar = Calendar.current
        hour = calendar.component(.hour, from: date)
        minute = calendar.component(.minute, from: date)
    }

    static func == (lhs: Time, rhs: Time) -> Bool {
        return lhs.hour == rhs.hour && lhs.minute == rhs.minute
    }

    static func < (lhs: Time, rhs: Time) -> Bool {
        return (lhs.hour < rhs.hour) || (lhs.hour == rhs.hour && lhs.minute < rhs.minute)
    }

    static func create(time: String) -> Time? {
        let parts = time.split(separator: ":")
        if let hour = Int(parts[0]), let minute = Int(parts[1]) {
            return Time(hour: hour, minute: minute)
        }
        return nil
    }

    static func isOpen(open: Time, close: Time) -> Bool {
        let isClosingAfterMidnight = close.hour < open.hour ? true : false
        let currentTime = Time(Date())

        if isClosingAfterMidnight {
            return currentTime > close && currentTime < open ? false : true
        }
        return currentTime >= open && currentTime < close
    }
}
