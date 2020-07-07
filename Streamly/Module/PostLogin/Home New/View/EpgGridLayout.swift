//
//  EpgGridLayout.swift
//  EPG
//
//  Created by Adnan Aftab on 2/14/15.
//  Copyright (c) 2015 CX. All rights reserved.
//

import UIKit

class EpgGridLayout: UICollectionViewLayout
{
    let cellIdentifier = "programCell"
    let RELATIVE_HOUR : TimeInterval = (240.0)
    let ACTUAL_HOUR : TimeInterval = 3600.0

    var epgStartTime : Date!
    var epgEndTime : Date!
    var xPos:CGFloat = 0
    var yPos:CGFloat = 0
    var layoutInfo : NSMutableDictionary?
    var framesInfo : NSMutableDictionary?
    
    let weekTimeInterval : TimeInterval = (60 * 60 * 24 * 3)
    let TILE_WIDTH : CGFloat = 395
    let TILE_HEIGHT : CGFloat = 120
    
    var channels : EPGResponse?
    
    override init() {
        super.init()
       // setup()
    }

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
      //  setup()
    }
    
//    func setup() {
//      //  print(channels!)
//        let cal = Calendar.current
//        var date = Date()
//        date = cal.startOfDay(for: date)
//        date = (cal as NSCalendar).date(byAdding: .day, value: -1, to: date, options: [])!
//        epgStartTime = date
//        epgEndTime = epgStartTime.addingTimeInterval(weekTimeInterval)
//        layoutInfo = NSMutableDictionary()
//        framesInfo = NSMutableDictionary()
//        channels = Channel.channels()
//    }
    
    override func prepare() {
        calculateFramesForAllPrograms()
        let newLayoutInfo = NSMutableDictionary()
        let cellLayoutInfo = NSMutableDictionary()
        guard let channels = channels?.data else { return }
        
        for section in 0..<channels.count {
            let channel = channels[section]
            guard let programs = channel.programs else { continue }
            
            for index in 0..<programs.count {
                let indexPath = IndexPath(item: index, section: section)
                let itemAttributes = UICollectionViewLayoutAttributes(forCellWith: indexPath)
                itemAttributes.frame = frameForItemAtIndexPath(indexPath)
                cellLayoutInfo[indexPath] = itemAttributes
            }
        }
        newLayoutInfo[cellIdentifier] = cellLayoutInfo
        layoutInfo = newLayoutInfo
        
    }

    override func layoutAttributesForElements(in rect: CGRect) -> [UICollectionViewLayoutAttributes]? {
        var layoutAttributes  = [UICollectionViewLayoutAttributes]()
        
        let enumerateClosure = { (object: Any, attributes: Any, stop: UnsafeMutablePointer<ObjCBool>) in
            guard let attributes = attributes as? UICollectionViewLayoutAttributes, rect.intersects(attributes.frame) else { return }
            layoutAttributes.append(attributes)
        }
        
        layoutInfo?.enumerateKeysAndObjects({ (object: Any, elementInfo: Any, stop: UnsafeMutablePointer<ObjCBool>) in
            guard let infoDic = elementInfo as? NSDictionary else { return }
            infoDic.enumerateKeysAndObjects(enumerateClosure)
        })
        
        return layoutAttributes
    }
    override func layoutAttributesForItem(at indexPath: IndexPath) -> (UICollectionViewLayoutAttributes?) {
        let attributes = UICollectionViewLayoutAttributes(forCellWith: indexPath)
        attributes.frame = self.frameForItemAtIndexPath(indexPath)
        return attributes
    }

    func tileSize(for program : ProgramArray) -> CGSize {
        let duartionFactor = program.duration / ACTUAL_HOUR
        let width :CGFloat =  CGFloat(duartionFactor) * TILE_WIDTH
        return CGSize(width: width, height: TILE_HEIGHT)
    }
    
    func calculateFramesForAllPrograms() {
        guard let channels = channels?.data else { return }
        for i in 0..<channels.count {
            xPos = 0
            let channel = channels[i]
            guard let programs = channel.programs else {
                yPos += TILE_HEIGHT
                    //+ 9.0
                continue
            }
            for index in 0..<programs.count {
                let program = programs[index]
                let tileSize = self.tileSize(for: program)
                let frame = CGRect(x: xPos, y: yPos, width: tileSize.width, height: tileSize.height)
                let rectString = NSCoder.string(for: frame)
                let indexPath = IndexPath(item: index, section: i)
                framesInfo?[indexPath] = rectString
                xPos = xPos+tileSize.width
                    //+ 9.0
            }
            yPos += TILE_HEIGHT
                //+ 9.0
        }
    }
    
    func frameForItemAtIndexPath(_ indexPath : IndexPath) -> CGRect {
        guard let infoDic = framesInfo, let rectString = infoDic[indexPath] as? String else { return CGRect.zero }
        return NSCoder.cgRect(for: rectString)
    }
    
    override var collectionViewContentSize : CGSize {
        guard let channels = channels?.data else { return CGSize.zero }
        
        let intervals = epgEndTime.timeIntervalSince(epgStartTime)
        let numberOfHours = CGFloat(intervals / 3600)
        let width = numberOfHours * TILE_WIDTH
        let height = CGFloat(channels.count) * TILE_HEIGHT
        return CGSize(width: width, height: height)
    }
    override func shouldInvalidateLayout(forBoundsChange newBounds: CGRect) -> Bool {
        return !collectionView!.bounds.size.equalTo(newBounds.size)
    }
    override func invalidateLayout() {
        xPos = 0
        yPos = 0
        super.invalidateLayout()
    }
}
