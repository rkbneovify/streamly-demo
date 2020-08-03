//
//  Protocols.swift
//  Streamly
//
//  Created by Rohit Singh on 6/9/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

protocol CustomViews:NSObject {
    
    func callAction(_ tag:Int)
}

protocol Scroll:NSObject {
    
    func scrollCollectionView(_ index:Int)
}

protocol HandleError:NSObject {
    
    func handleError(_ message:String)
}

protocol AddRecentData:NSObject {
    func addRecentData(_ data:RecentData)
}

